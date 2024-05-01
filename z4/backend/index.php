<?php
header('Content-Type: application/json');
require_once "/var/www/node86.webte.fei.stuba.sk/z4/backend/php/dbConnect.php";
require_once "/var/www/node86.webte.fei.stuba.sk/z4/backend/config.php";
$pdo = getPDODbConnection();

session_start();

if ($_SERVER['REQUEST_URI'] === '/weather-api/start-session') {
    echo json_encode(array("session_status" => "started", "message" => "Session has been started successfully."));
}

function hashIpAddress($ipAddress)
{
    $salt = 'unique_salt';
    return hash('sha256', $ipAddress . $salt);
}

function registerVisit($pdo)
{
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $hashed_ip = hashIpAddress($ip_address);

    $stmt = $pdo->prepare("SELECT visit_time FROM visits WHERE ip_hash = ? ORDER BY visit_time DESC LIMIT 1");
    $stmt->execute([$hashed_ip]);
    $lastVisit = $stmt->fetch();

    $currentTime = new DateTime();
    if (!$lastVisit || $currentTime->getTimestamp() - strtotime($lastVisit['visit_time']) > 3600) {
        $stmt = $pdo->prepare("INSERT INTO visits (ip_hash, visit_time) VALUES (?, NOW())");
        $stmt->execute([$hashed_ip]);
    }
}

registerVisit($pdo);


$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_URI'] === '/weather-api/get-info' && $requestMethod === "POST") {
    function getCurrentPlaceInfo($latitude, $longitude, $startDate, $endDate)
    {
        $dailyParams = 'temperature_2m_max,temperature_2m_min,precipitation_sum,windspeed_10m_max,weathercode';

        $url = "https://api.open-meteo.com/v1/forecast?"
            . "latitude={$latitude}"
            . "&longitude={$longitude}"
            . "&start_date={$startDate}"
            . "&end_date={$endDate}"
            . "&daily={$dailyParams}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            if (isset($response)) {
                return json_decode($response, true);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    function getPlaceInfo($cityName, $apiKey)
    {
        $url = 'https://api.api-ninjas.com/v1/city?name=' . urlencode($cityName);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'X-Api-Key: ' . $apiKey
            )
        );

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            echo 'cURL Error: ' . $err;
        } else {
            return json_decode($response, true);
        }
    }

    function getCountryInfoByCountryCode($countryCode)
    {
        $url = "https://restcountries.com/v2/alpha/{$countryCode}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $countryData = json_decode($response, true);
        $currencyCode = $countryData['currencies'][0]['code'];


        $exchangeRate = 1;
        $exchangeRateApiKey = ER_API_KEY;

        if ($currencyCode !== 'EUR') {
            $exchangeRateUrl = "https://v6.exchangerate-api.com/v6/{$exchangeRateApiKey}/latest/{$currencyCode}";
            $ch = curl_init($exchangeRateUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $exchangeRateResponse = curl_exec($ch);
            curl_close($ch);

            if ($exchangeRateResponse) {
                $exchangeData = json_decode($exchangeRateResponse, true);
                if ($exchangeData['result'] === 'success') {
                    $exchangeRate = $exchangeData['conversion_rates']['EUR'];
                } else {
                    $exchangeRate = null;
                }
            }
        }

        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['capital'])) {
                return [
                    'capital' => $data['capital'],
                    'currencies' => $data['currencies'],
                    'exchange_rate' => $exchangeRate,
                    'country' => $data['name'],
                    'flag' => $data['flags']['png'],
                ];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    function recordSearch($pdo, $destination, $countryCode)
    {
        $stmt = $pdo->prepare("SELECT id, search_count FROM Searches WHERE destination_name = ? AND country = ?");
        $stmt->execute([$destination, $countryCode]);
        $search = $stmt->fetch();

        if ($search) {
            $stmt = $pdo->prepare("UPDATE Searches SET search_count = search_count + 1 WHERE id = ?");
            $stmt->execute([$search['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO Searches (destination_name, country, search_count) VALUES (?, ?, 1)");
            $stmt->execute([$destination, $countryCode]);
        }
    }

    $requestUri = strtolower(explode('?', $_SERVER['REQUEST_URI'])[0]);

    $content = file_get_contents("php://input");
    $data = json_decode($content);

    $cityName = $data->city_name;
    $startDate = $data->start_date;
    $endDate = $data->end_date;

    $placeInfo = getPlaceInfo($cityName, API_KEY);
    $countryInfo = getCountryInfoByCountryCode($placeInfo[0]['country']);

    if ($placeInfo) {
        $currentPlaceInfo = getCurrentPlaceInfo($placeInfo[0]['latitude'], $placeInfo[0]['longitude'], $startDate, $endDate);

        $currentPlaceInfo['city_name'] = $cityName;
        $currentPlaceInfo['capital'] = $capital;
        $currentPlaceInfo = array_merge($currentPlaceInfo, $countryInfo);
        recordSearch($pdo, $cityName, $countryInfo['country']);
        echo json_encode($currentPlaceInfo);
    }
}
flush();
?>