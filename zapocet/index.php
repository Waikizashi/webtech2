<?php
header('Content-Type: application/json');

define('DB_HOST', 'localhost');
define('DB_NAME', 'meals');
define('DB_USER', 'xpanarin');
define('DB_PASS', 'veryhardpa$$');
define('DB_CHARSET', 'utf8mb4');

function getPDODbConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    return $pdo;
}



function getMeals($pdo) {
    try{
    $stmt = $pdo->query('SELECT * FROM meal');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    http_response_code(400);
    die ("PDO_ERROR: " . $e->getMessage());
}
}
function getMealById($pdo, $id) {
    try{
    $stmt = $pdo->prepare('SELECT * FROM meal WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    http_response_code(400);
    die ("PDO_ERROR: " . $e->getMessage());
}
}

function addMeal($pdo, $name, $allergens) {
    try{
    $stmt = $pdo->prepare('INSERT INTO meal (name, allergens) VALUES (:name, :allergens)');
    $stmt->execute([
        ':name' => $name,
        ':allergens' => $allergens,
    ]);
    return $pdo->lastInsertId();
} catch (PDOException $e) {
    http_response_code(400);
    die ("PDO_ERROR: " . $e->getMessage());
}
}

function updateMeal($pdo, $id, $name, $allergens) {
    try{
    $stmt = $pdo->prepare('UPDATE meal SET name = :name, allergens = :allergens WHERE id = :id');
    $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':allergens' => $allergens,
    ]);
    $stmt = $pdo->prepare('SELECT * FROM meal WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    http_response_code(400);
    die ("PDO_ERROR: " . $e->getMessage());
}
}

function deleteMeal($pdo, $id) {
    try{
    $stmt = $pdo->prepare('DELETE FROM meal WHERE id = :id');
    $stmt->execute([
        ':id' => $id
    ]);
} catch (PDOException $e) {
    http_response_code(400);
    die ("PDO_ERROR: " . $e->getMessage());
}
}

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = strtolower(explode('?', $_SERVER['REQUEST_URI'])[0]);
$basePath = '/api';
$uri = substr($requestUri, strlen($basePath));
$uriSegments = explode('/', $uri);

if ($uriSegments[1] === 'meals') {
    if (isset($uriSegments[2]) && is_numeric($uriSegments[2])) {
        $mealId = (int) $uriSegments[2];
    }
        $pdo = getPDODbConnection();
        switch ($requestMethod) {
            case 'GET':                
                if(isset($mealId)){
                    echo getMealById($pdo, $mealId);
                }else{
                   echo getMeals($pdo);
                }
                break;
            case 'POST':
                $content = file_get_contents("php://input");
                $data = json_decode($content);
                if ($data !== null && isset($data[0]->name) && isset($data[0]->allergens) ) {
                    echo addMeal($pdo, $data[0]->name, $data[0]->allergens);
                } else {
                    echo json_encode(['error' => 'OMG maaaaan! missing data! ' . $requestUri . ' ' . $requestMethod]);
                    http_response_code(400);
                    break;
                }

                break;
            case 'PUT':
                $content = file_get_contents("php://input");
                $data = json_decode($content);
                if ($data !== null && isset($data[0]->name) && isset($data[0]->allergens) && isset($mealId)) {
                    echo updateMeal($pdo, $mealId, $data[0]->name, $data[0]->allergens);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'OMG maaaaan! missing data!! ' . $requestUri . ' ' . $requestMethod]);
                    break;
                }

                break;
            case 'DELETE':

                if(isset($mealId)){
                        echo deleteMeal($pdo, $mealId);
                } else {

                    http_response_code(400);
                    echo json_encode(['error' => 'OMG maaaaan! missing data!! ' . $requestUri . ' ' . $requestMethod . " " . $id . " " . $all]);
                }

                break;
            default:
                echo json_encode(['error' => 'OMG maaaaan! wrong _REQUEST_METHOD_! ' . $requestUri . ' ' . $requestMethod]);
                break;
        }
}
flush();
?>