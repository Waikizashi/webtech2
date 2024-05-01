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
function getVisitsStatistics($pdo)
{
    $sql = "SELECT
                CASE
                    WHEN HOUR(visit_time) BETWEEN 6 AND 14 THEN '6:00-15:00'
                    WHEN HOUR(visit_time) BETWEEN 15 AND 20 THEN '15:00-21:00'
                    WHEN HOUR(visit_time) BETWEEN 21 AND 23 THEN '21:00-24:00'
                    ELSE '24:00-6:00'
                END AS time_frame,
                COUNT(*) AS visit_count
            FROM visits
            WHERE visit_time > NOW() - INTERVAL 1 DAY
            GROUP BY time_frame
            ORDER BY NULL";

    $stmt = $pdo->query($sql);

    return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}


echo getVisitsStatistics($pdo);
?>