<?php
// YourApp/Game.php
namespace socketApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Game implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Сохраняем новое соединение
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Обрабатываем сообщение от клиента
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // Отправляем сообщение всем клиентам, кроме отправителя
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Удаляем соединение
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Обрабатываем ошибки
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
?>