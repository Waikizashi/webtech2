<?php
header('Content-Type: application/json');

ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1');
ini_set("session.use_cookies", '1');
session_start();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = strtolower(explode('?', $_SERVER['REQUEST_URI'])[0]);

if (!isset($_SESSION['auth_token'])) {
    $_SESSION['auth_token'] = null;
}
if (!isset($_SESSION['logged_in'])) {
    $_SESSION['logged_in'] = null;
}

if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
    $token = $_SERVER['HTTP_AUTHORIZATION'];
    if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
        $token = $matches[1];
        if ($token === $_SESSION['auth_token']) {
            $_SESSION['logged_in'] = true;
        }
    }
}

if ($requestMethod !== 'GET' && $_SESSION['logged_in'] !== true && $requestUri !== "/api/reg" && $requestUri !== "/api/login") {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'You must be authorized' . $requestUri]);
    exit;
}

require_once 'service/NobelService.php';
require_once 'service/GoogleUserService.php';
require_once 'service/UserService.php';



$googleUserService = new GoogleUserService();
$userService = new UserService();
$nobelService = new NobelService();

switch ($requestUri) {
    case '/nobel-api/session':
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo json_encode(['logged_in' => true]);
        } else {
            echo json_encode(['logged_in' => false]);
        }
        break;
    case '/nobel-api/prizes':
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : -1;
        $perPage = filter_input(INPUT_GET, 'perpage', FILTER_VALIDATE_INT) && $_GET['perpage'] > 0
            ? (int) $_GET['perpage']
            : -1;

        $sortByYear = $_GET['sortbyyear'] ?? null;
        $sortByYear = strtoupper($sortByYear);
        $sortByYear = in_array($sortByYear, ['ASC', 'DESC'], true) ? $sortByYear : null;

        $sortByName = $_GET['sortbyname'] ?? null;
        $sortByName = strtoupper($sortByName);
        $sortByName = in_array($sortByName, ['ASC', 'DESC'], true) ? $sortByName : null;

        $sortByCategory = $_GET['sortbycategory'] ?? null;
        $sortByCategory = strtoupper($sortByCategory);
        $sortByCategory = in_array($sortByCategory, ['ASC', 'DESC'], true) ? $sortByCategory : null;
        $sortParamCheck = ($sortByYear !== null) + ($sortByName !== null) + ($sortByCategory !== null);

        $yearFilter = filter_input(INPUT_GET, 'yearfilter', FILTER_VALIDATE_INT) && $_GET['yearfilter'] > 1900
            ? (int) $_GET['yearfilter']
            : null;
        $сategoryFilter = $_GET['categoryfilter'] ?? null;
        $сategoryFilter = in_array($сategoryFilter, ['fyzika', 'chémia', 'medicína', 'mier', 'literatúra'], true) ? $сategoryFilter : null;

        if ($sortParamCheck > 1) {
            echo json_encode(['error' => 'O k**wa! wrong _PARAMS_! ' . $requestUri . ' ' . $requestMethod]);
            break;
        }
        if ($sortByYear) {
            echo $nobelService->getPrizesSortedByYear($perPage, ($page - 1) * $perPage, $sortByYear, $yearFilter, $сategoryFilter);
        } else if ($sortByName) {
            echo $nobelService->getPrizesSortedByName($perPage, ($page - 1) * $perPage, $sortByName, $yearFilter, $сategoryFilter);
        } else if ($sortByCategory) {
            echo $nobelService->getPrizesSortedByCategory($perPage, ($page - 1) * $perPage, $sortByCategory, $yearFilter, $сategoryFilter);
        } else {
            echo $nobelService->getPrizes($perPage, ($page - 1) * $perPage, $yearFilter, $сategoryFilter);
        }
        break;
    case '/nobel-api/prize':
        switch ($requestMethod) {
            case 'GET':
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) && $_GET['id'] > 0
                    ? (int) $_GET['id']
                    : null;
                if ($id === null) {
                    echo json_encode(['error' => 'O k**wa! wrong _ID_! ' . $requestUri . ' ' . $requestMethod]);
                    break;
                }
                echo $nobelService->getPrizeById($id);
                break;
            case 'POST':
                $content = file_get_contents("php://input");
                $data = json_decode($content);
                echo $nobelService->createPrize($data);
                break;
            case 'PUT':
                $content = file_get_contents("php://input");
                $data = json_decode($content);
                // echo json_encode($data);
                echo $nobelService->updatePrize($data);
                break;
            case 'DELETE':
                $content = file_get_contents("php://input");
                $data = json_decode($content);
                $id = $data->id;
                if ($id === null) {
                    echo json_encode(['error' => 'O k**wa! wrong _ID_! ' . $requestUri . ' ' . $requestMethod]);
                    break;
                }
                echo $nobelService->deleteReceiverById($id);
                break;
            default:
                echo json_encode(['error' => 'O k**wa! wrong _REQUEST METHOD_! ' . $requestUri . ' ' . $requestMethod]);
                break;
        }
        break;
    case '/nobel-api/nobel-receiver':
        switch ($requestMethod) {
            case 'GET':
                $recid = filter_input(INPUT_GET, 'recid', FILTER_VALIDATE_INT) && $_GET['recid'] > 0
                    ? (int) $_GET['recid']
                    : null;
                if ($recid === null) {
                    echo json_encode(['error' => 'O k**wa! wrong _ID_! ' . $requestUri . ' ' . $requestMethod]);
                    break;
                }
                echo $nobelService->getReceiverById($recid);
                break;
            default:
                echo json_encode(['error' => 'O k**wa! wrong _REQUEST METHOD_! ' . $requestUri . ' ' . $requestMethod]);
                break;
        }
        break;
    case '/nobel-api/categories':
        echo $nobelService->getCategories();
        break;
    case '/nobel-api/auth-url':
        echo $googleUserService->getLoginUrl();
        break;
    case '/nobel-api/login':
        switch ($requestMethod) {
            case 'GET':
                if (isset($_GET['code'])) {
                    echo $googleUserService->authenticate($_GET['code']);
                } else {
                    echo json_encode(['error' => 'O k**wa! missing _AUTH DATA_! ' . $requestUri . ' ' . $requestMethod]);
                }
                break;
            case 'POST':
                $content = file_get_contents("php://input");
                $data = json_decode($content);
                $user_email = $data->email;
                $user_pass = $data->pass;
                echo $userService->authenticate($user_email, $user_pass);
                break;
        }
        break;
    case '/nobel-api/logout':
        $userService->logout();
        break;
    case '/nobel-api/reg':
        switch ($requestMethod) {
            case 'POST':
                $content = file_get_contents("php://input");
                $data = json_decode($content);
                $user_name = $data->name;
                $user_surname = $data->surname;
                $user_email = $data->email;
                $user_pass = $data->pass;
                if (isset($user_name) && isset($user_surname) && isset($user_email) && isset($user_pass)) {
                    echo $userService->registerNewUser($user_name, $user_surname, $user_email, $user_pass);
                } else {
                    echo json_encode(['error' => 'O k**wa! wrong _REG DATA_! ' . $requestUri . ' ' . $requestMethod]);
                }
                break;
            default:
                echo json_encode(['error' => 'O k**wa! wrong _METHOD_! ' . $requestUri . ' ' . $requestMethod]);
                break;

        }
        break;
    default:
        echo json_encode(['error' => 'O k**wa! wrong _URL_! ' . $requestUri . ' ' . $requestMethod]);
        break;
}
flush();
?>