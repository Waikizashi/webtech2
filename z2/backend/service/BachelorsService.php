<?php
require_once "php/dbConnect.php";

define('USER_AGENT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36');
define('COOKIE_FILE', 'cookie.txt');
define('LOGIN_FORM_URL', 'https://is.stuba.sk/system/login.pl');
define('LOGIN_ACTION_URL', 'https://is.stuba.sk/auth/');


class BachelorsService
{
    public function __construct()
    {
        // $pdo = getPDODbConnection();
        // $this->pdo = $pdo;
    }
    public function parseBachelors(int $pracoviste)
    {
        $url = "https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=$pracoviste";

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);

        $htmlContent = curl_exec($curl);

        if (curl_errno($curl)) {
            // echo 'Request Error:' . curl_error($curl);
        } else {
            // echo $htmlContent; 
        }

        // Close the cURL session
        curl_close($curl);
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent);
        $xpath = new DOMXPath($dom);

        $rows = $xpath->query("//form//table[./thead]/tbody/tr");
        $data = [];

        foreach ($rows as $row) {
            $cols = $xpath->query(".//td", $row);
            $obsadeneMax = trim($cols->item(9)->nodeValue);

            list($obsadene, $max) = array_map('trim', explode('/', $obsadeneMax));

            if ($obsadene === $max) {
                continue;
            }

            $detailsLink = $xpath->query(".//td[9]//a", $row)->item(0);
            $href = $detailsLink ? trim($detailsLink->getAttribute('href')) : '';
            $abstrakt = '';
            if ($href) {
                $abstrakt = $this->getAbstrakt($href);
            }

            $item = [
                "Por" => trim($cols->item(0)->nodeValue),
                "Typ" => trim($cols->item(1)->nodeValue),
                "Názov témy" => trim($cols->item(2)->nodeValue),
                "Vedúci práce" => trim($cols->item(3)->nodeValue),
                "Garantujúce pracovisko" => trim($cols->item(4)->nodeValue),
                "Program" => trim($cols->item(5)->nodeValue),
                "Zameranie" => trim($cols->item(6)->nodeValue),
                "Určené pre" => trim($cols->item(7)->nodeValue),
                "Podrobnosti" => trim($cols->item(8)->nodeValue),
                "Obsadené/Max" => trim($cols->item(9)->nodeValue),
                "Riešitelia" => trim($cols->item(10)->nodeValue),
                "Abstrakt" => $abstrakt
            ];
            $data[] = $item;
        }

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    private function getAbstrakt($href)
    {
        $abstraktUrl = "https://is.stuba.sk" . $href;
        $curl = curl_init($abstraktUrl);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);

        $htmlContent = curl_exec($curl);
        curl_close($curl);

        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent);
        $xpath = new DOMXPath($dom);

        $abstraktRows = $xpath->query("//tr[contains(td/b, 'Abstrakt:')]");
        if ($abstraktRows->length > 0) {
            $abstraktTd = $xpath->query(".//td[2]", $abstraktRows->item(0));
            if ($abstraktTd->length > 0) {
                return trim($abstraktTd->item(0)->nodeValue);
            }
        }
        return '';
    }

}
