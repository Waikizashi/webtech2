<?php
require_once "php/dbConnect.php";

class TimetableService
{
    private string $cookie;
    private $pdo;
    public function __construct()
    {
        $this->getToken();
        $pdo = getPDODbConnection();
        $this->pdo = $pdo;
    }

    public function getTimetable($fetchTT = '')
    {
        $timetabeldata = $this->fetchTimetable();

        if ($fetchTT === 'yes') {
            $data = json_decode($timetabeldata);
            try {
                $checkSql = "SELECT COUNT(*) FROM timetable WHERE day = :day AND type = :type AND auditorium = :auditorium AND subject = :subject AND teacher = :teacher";
                $checkStmt = $this->pdo->prepare($checkSql);

                $insertSql = "INSERT INTO timetable (day, type, auditorium, subject, teacher) VALUES (:day, :type, :auditorium, :subject, :teacher)";
                $insertStmt = $this->pdo->prepare($insertSql);

                foreach ($data as $item) {
                    $checkStmt->execute([
                        ':day' => $item->Day,
                        ':type' => $item->type,
                        ':auditorium' => $item->auditorium,
                        ':subject' => $item->subject,
                        ':teacher' => $item->teacher
                    ]);
                    if ($checkStmt->fetchColumn() == 0) {
                        $insertStmt->execute([
                            ':day' => $item->Day,
                            ':type' => $item->type,
                            ':auditorium' => $item->auditorium,
                            ':subject' => $item->subject,
                            ':teacher' => $item->teacher
                        ]);
                    }
                }

                http_response_code(200);
                $sql = "SELECT * FROM timetable";
                $stmt = $this->pdo->query($sql);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } catch (PDOException $e) {
                http_response_code(400);
                die ("DATA_FETCHING_ERROR: " . $e->getMessage());
            }
        } else {
            http_response_code(200);
            $sql = "SELECT * FROM timetable";
            $stmt = $this->pdo->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }
    public function updateTimetable($data)
    {
        try {
            $updateQuery = "UPDATE timetable SET day = :day, type = :type, auditorium = :auditorium, subject = :subject, teacher = :teacher WHERE id = :id";
            $updateStmt = $this->pdo->prepare($updateQuery);

            foreach ($data as $item) {
                $updateStmt->execute([
                    ':id' => $item->id,
                    ':day' => $item->day,
                    ':type' => $item->type,
                    ':auditorium' => $item->auditorium,
                    ':subject' => $item->subject,
                    ':teacher' => $item->teacher
                ]);
            }
            http_response_code(200);
            $sql = "SELECT * FROM timetable";
            $stmt = $this->pdo->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (PDOException $e) {
            http_response_code(400);
            die ("DATA_FETCHING_ERROR: " . $e->getMessage());

        }
    }
    function deleteTimetableRecordById($id)
    {
        try {
            $sql = "DELETE FROM timetable WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            http_response_code(200);
        } catch (PDOException $e) {
            http_response_code(400);
            die ("DATA_FETCHING_ERROR: " . $e->getMessage());

        }
    }
    function deleteAllRecords()
    {
        try {
            $sql = "DELETE FROM timetable";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            http_response_code(200);
        } catch (PDOException $e) {
            http_response_code(400);
            die ("DATA_FETCHING_ERROR: " . $e->getMessage());

        }
    }
    function createTimetableRecord($record)
    {
        try {
            $sql = "INSERT INTO timetable (day, type, auditorium, subject, teacher) VALUES (:day, :type, :auditorium, :subject, :teacher)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':day' => $record->day,
                ':type' => $record->type,
                ':auditorium' => $record->auditorium,
                ':subject' => $record->subject,
                ':teacher' => $record->teacher
            ]);
            $lastId = $this->pdo->lastInsertId();

            $sql = "SELECT * FROM timetable WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $lastId]);
            $newRecord = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(200);
            return json_encode($newRecord, JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(400);
            die ("CREATING_ERROR: " . $e->getMessage());

        }
    }


    private function fetchTimetable()
    {

        $url = 'https://is.stuba.sk/auth/katalog/rozvrhy_view.pl?rozvrh_student_obec=1?zobraz=1;format=html;rozvrh_student=109857;zpet=../student/moje_studium.pl?_m=3110,lang=en,studium=161575,obdobi=630;lang=en';
        $curl = curl_init($url);
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36',
            'Cookie: ' . $this->cookie,
        ];
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $htmlContent = curl_exec($curl);
        curl_close($curl);
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent);
        $xpath = new DOMXPath($dom);
        $daysRows = $xpath->query("//td[contains(@class, 'zahlavi') and @align='left']");
        $days = [];
        foreach ($daysRows as $dayRow) {
            $day = trim($dayRow->nodeValue);
            $day === '' ? null : $days[] = $day;
        }

        $classesInfo = [];
        $currentDayIndex = -1;

        $rows = $xpath->query("//table[./thead]//tbody//tr");
        foreach ($rows as $row) {
            $dayCells = $xpath->query(".//td[contains(@class, 'zahlavi') and @align='left']", $row);
            if ($dayCells->length > 0) {
                $dayCellText = trim($dayCells->item(0)->nodeValue);
                if (!empty ($dayCellText)) {
                    $currentDayIndex++;
                }
            }
            $classes = $xpath->query(".//td[contains(@class, 'rozvrh-cvic') or contains(@class, 'rozvrh-pred')]", $row);
            foreach ($classes as $class) {
                $classType = strpos($class->getAttribute('class'), 'rozvrh-cvic') !== false ? 'seminar' : 'lecture';
                $info = [
                    "Day" => $days[$currentDayIndex],
                    'type' => $classType,
                    'auditorium' => '',
                    'subject' => '',
                    'teacher' => ''
                ];

                $auditoriumLink = $xpath->query(".//a[contains(@href, 'mistnosti')]", $class);
                if ($auditoriumLink->length > 0) {
                    $info['auditorium'] = trim($auditoriumLink->item(0)->nodeValue);
                }

                $subjectLink = $xpath->query(".//a[contains(@href, 'syllabus')]", $class);
                if ($subjectLink->length > 0) {
                    $info['subject'] = trim($subjectLink->item(0)->nodeValue);
                }

                $teacherLink = $xpath->query(".//i/a[contains(@href, 'clovek.pl')]", $class);
                if ($teacherLink->length > 0) {
                    $info['teacher'] = trim($teacherLink->item(0)->nodeValue);
                }
                $classesInfo[] = $info;
            }
        }

        return json_encode($classesInfo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    private function getToken()
    {
        $url = 'https://is.stuba.sk/auth/?lang=en&login_hidden=1&destination=/auth/?lang=en';
        $postData = [
            'lang' => 'en',
            'login_hidden' => '1',
            'destination' => '/auth?lang=en',
            'auth_id_hidden' => '0',
            'auth_2fa_type' => 'no',
            'credential_0' => 'xpanarin',
            'credential_1' => 'LetsBeg11n',
            'credential_k' => '',
            'credential_2' => '86400',
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $err = 'cURL err: ' . curl_error($curl);
            curl_close($curl);
            echo $err;
        } else {
            preg_match('/UISAuth=([^;]+)/', $response, $matches);
            $uisAuthValue = $matches[1] ?? null;
            curl_close($curl);
            $this->cookie = "UISAuth=" . $uisAuthValue;
            return "UISAuth=" . $uisAuthValue;
        }

    }

}
