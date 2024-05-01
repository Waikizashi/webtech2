<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'weather_app');
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
$pdo = getPDODbConnection();
function getSearchStatistics($pdo)
{
    $stmt = $pdo->query("SELECT destination_name, country, search_count FROM Searches ORDER BY search_count DESC");
    return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

echo getSearchStatistics($pdo);
?>