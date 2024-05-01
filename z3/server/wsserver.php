<?php
require dirname(__DIR__) . '/backend/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Connection\TcpConnection;

$ws_worker = new Worker("websocket://127.0.0.1:8282");
$ws_worker->count = 1;

$players = [];
$playersCount = 0;
$started = false;
$ws_worker->onWorkerStart = function () use (&$players) {
    $players = [
        'byId' => [0 => null, 1 => null],
        'byConnectionId' => []
    ];
};

$ws_worker->onConnect = function ($connection) use (&$players) {
    
};

$ws_worker->onMessage = function (TcpConnection $connection, $data) use ($ws_worker, &$players) {
    $data = json_decode($data, true);

    if (isset($data['type']) && $data['type'] === 'clearTrail') {
        if (isset($players[$connection->id])) {
            $players[$connection->id]['trail'] = []; 

            $clearConfirmMessage = json_encode([
                'type' => 'trailCleared',
                'playerId' => $connection->id
            ]);
            $connection->send($clearConfirmMessage);
        }
        return;
    }
    if ($started == false && isset($data['type']) && $data['type'] === 'newPlayer') {

                $uuid = uniqid();
    $colors = ['red', 'green', 'blue', 'yellow', 'purple', 'orange'];
    $usedColors = array_column($players, 'color');

    $color = null;
    foreach ($colors as $possibleColor) {
        if (!in_array($possibleColor, $usedColors)) {
            $color = $possibleColor;
            break;
        }
    }

    $playerId = null;
    foreach ($players['byId'] as $id => $value) {
        if ($value === null) {
            $playerId = $id;
            break;
        }
    }


    if ($playerId === null) {
        $connection->close();
        return;
    }


    $players['byId'][$playerId] = $connection;
    $players['byConnectionId'][$connection->id] = $playerId;

    $players[$connection->id] = [
        'uuid' => $uuid,
        'id' => $playerId,
        'color' => $color,
        'x' => 0,
        'y' => 0,
        'trail' => [[0, 0]]
    ];


    $connection->send(json_encode([
        "type" => "playerData",
        "id" => $playerId,
        "uuid" => $uuid,
        "color" => $color
    ]));
    $connection->uuid = $uuid;
        if(count($players) < 4){
            $gameStartMessage = json_encode([
                'type' => 'gamePending',
                'playercount' => count($players)
            ]);
            foreach ($ws_worker->connections as $conn) {
                $conn->send($gameStartMessage);
            }
        }
    if(count($players) == 4){
        $started = true;
        $gameStartMessage = json_encode([
            'type' => 'gameStart',
            'playercount' => count($players)
        ]);
        foreach ($ws_worker->connections as $conn) {
            $conn->send($gameStartMessage);
        }
    }
    
    return;
        
    }

    if (isset ($data['x']) && isset ($data['y'])) {
        $players[$connection->id]['x'] = $data['x'];
        $players[$connection->id]['y'] = $data['y'];
        $players[$connection->id]['speed'] = $data['speed'] ?? 1;
        $players[$connection->id]['occupiedArea'] = $data['occupiedArea'] ?? [];

        if (!isset ($players[$connection->id]['trail'])) {
            $players[$connection->id]['trail'] = [];
        }

        foreach ($players as $id => $player) {
            if (!isset ($player['trail']) || !is_array($player['trail'])) {
                $player['trail'] = []; // Инициализируем 'trail', если он не установлен или не массив
            }

            if ($id !== $connection->id && in_array(['x' => $data['x'], 'y' => $data['y']], $player['trail'])) {
                $killedBy = $players[$connection->id]['id']; // Предположим, что у вас есть 'name' в массиве $players
                $gameOverMessage = json_encode([
                    'type' => 'gameOver',
                    'reason' => 'hitOtherPlayer',
                    'killedBy' => $killedBy,
                    'victim' => $player['id'] // имя жертвы
                ]);
                $connection->send($gameOverMessage);

                // Остановка игры для всех игроков
                foreach ($ws_worker->connections as $conn) {
                    $conn->send($gameOverMessage);
                }
                return; // Выход из обработчика
            }
        }

        // Проверка на самопересечение трейла, исключая текущую позицию
        // $trailPositions = array_count_values(array_map(function ($e) {
        //     return "{$e['x']},{$e['y']}";
        // }, $players[$connection->id]['trail']));

        // if (isset ($trailPositions["{$data['x']},{$data['y']}"]) && $trailPositions["{$data['x']},{$data['y']}"] > 1) {
        //     $gameOverMessage = json_encode([
        //         'type' => 'gameOver',
        //         'reason' => 'hitSelf',
        //         'victim' => $players[$connection->id]['name'] // имя игрока, который убил себя
        //     ]);
        //     $connection->send($gameOverMessage);

        //     // Остановка игры для всех игроков
        //     foreach ($ws_worker->connections as $conn) {
        //         $conn->send($gameOverMessage);
        //     }
        //     return; // Выход из обработчика
        // }

        $players[$connection->id]['trail'][] = ['x' => $data['x'], 'y' => $data['y']];

        $updatedData = json_encode([
            "type" => "otherPlayer",
            'uuid' => $connection->uuid,
            "color" => $players[$connection->id]['color'],
            'x' => $data['x'],
            'y' => $data['y'],
            'speed' => $players[$connection->id]['speed'],
            'occupiedArea' => $players[$connection->id]['occupiedArea'],
            'trail' => $players[$connection->id]['trail'],
            'playerId' => $players[$connection->id]['id']
            
        ]);

        foreach ($ws_worker->connections as $conn) {
            if ($conn !== $connection) {
                $conn->send($updatedData);
            }
        }
    }
};


$ws_worker->onClose = function ($connection) use (&$players) {
    $playerId = $players['byConnectionId'][$connection->id] ?? null;
    if ($playerId !== null) {

        $players['byId'][$playerId] = null;
    }

    unset ($players['byConnectionId'][$connection->id]);
    unset ($players[$connection->id]);
};

Worker::runAll();
?>