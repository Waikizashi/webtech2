<?php
header('Content-Type: application/json');

ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1');
ini_set("session.use_cookies", '1');
session_start();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = strtolower(explode('?', $_SERVER['REQUEST_URI'])[0]);

require_once 'service/BachelorsService.php';
require_once 'service/TimetableService.php';

$bachelorService = new BachelorsService();
$timetableService = new TimetableService();

switch ($requestUri) {
    case '/parse-api/timetable':
        switch ($requestMethod) {
            case 'GET':
                $fetch = $_GET['fetch'];
                echo $timetableService->getTimetable($fetch);
                break;
            case 'POST':
                $content = file_get_contents("php://input");
                $data = json_decode($content);
                if ($data === null) {
                    echo json_encode(['error' => 'O k**wa! missing data! ' . $requestUri . ' ' . $requestMethod]);
                    http_response_code(400);
                    break;
                } else {
                    echo $timetableService->createTimetableRecord($data);
                }

                break;
            case 'PUT':
                $content = file_get_contents("php://input");
                $data = json_decode($content);
                if ($data === null) {
                    http_response_code(400);
                    echo json_encode(['error' => 'O k**wa! missing data!! ' . $requestUri . ' ' . $requestMethod]);
                    break;
                } else {
                    echo $timetableService->updateTimetable($data);
                }

                break;
            case 'DELETE':
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) && $_GET['id'] > 0
                    ? (int) $_GET['id']
                    : -1;
                if ($id > -1) {
                    $timetableService->deleteTimetableRecordById($id);
                } else {
                    $all = $_GET['all'];
                    if ($all === 'true') {
                        $timetableService->deleteAllRecords();
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => 'O k**wa! missing data!! ' . $requestUri . ' ' . $requestMethod . " " . $id . " " . $all]);
                    }
                }

                break;
            default:
                echo json_encode(['error' => 'O k**wa! wrong _REQUEST_METHOD_! ' . $requestUri . ' ' . $requestMethod]);
                break;
        }
        break;
    case '/parse-api/bachelors':
        switch ($requestMethod) {
            case 'GET':
                $pracoviste = filter_input(INPUT_GET, 'pracoviste', FILTER_VALIDATE_INT) && $_GET['pracoviste'] > 0
                    ? (int) $_GET['pracoviste']
                    : -1;
                if ($pracoviste === -1) {
                    echo json_encode(['error' => 'Invalid pracoviste' . $requestUri . ' ' . $requestMethod]);
                }
                echo $bachelorService->parseBachelors($pracoviste);
                break;
            default:
                echo json_encode(['error' => 'O k**wa! wrong _REQUEST_METHOD_! ' . $requestUri . ' ' . $requestMethod]);
                break;
        }
        break;
    default:
        echo json_encode(['error' => 'O k**wa! wrong _URL_! ' . $requestUri . ' ' . $requestMethod]);
        break;
}
flush();
?>