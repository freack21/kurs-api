<?php
/*===========================================================================>

                   KURS CURRENCY kursdollar.org PHP - VERSION

        ____ ____ ____ ____ _  _    _  _    ____ _   _ ____ ___  ____ _  _ 
        |___ |__/ |___ |__| |_/      \/     [__   \_/  |__| |  \ |___ |  | 
        |    |  \ |___ |  | | \_    _/\_    ___]   |   |  | |__/ |___  \/  
                                                                   
Tersedia untuk : 

- USD
- SGD
- AUD
- EUR
- CNY
- HKD
- GBP
- JPY
- CAD
- NZD
- MYR
- THB
- SAR
- PHP
- KRW
- VND
- PGK
- LAK
- KWD
- BND

<===========================================================================*/

if (isset($_GET['kurs'])) {
    $currency = $_GET['kurs'];

$url = "https://kursdollar.org/real-time/" . $currency;
$content = file_get_contents($url);

$start = strpos($content, '<table class="in_table" width="100%">');
$end = strpos($content, '</table>', $start);
$table_content = substr($content, $start, $end - $start + strlen('</table>'));

$dom = new DOMDocument();
$dom->loadHTML($table_content);
$xpath = new DOMXPath($dom);

$matauang = $xpath->query('//h2')->item(0)->nodeValue;
$deskripsi = $xpath->query('//h1')->item(0)->nodeValue;
$nilai = $xpath->query('//td[contains(@style, "border-right")]')->item(0)->nodeValue;
$status = trim($xpath->query('//td[contains(@style, "border-right")]//font')->item(0)->nodeValue);
$status2 = trim($xpath->query('//td[contains(@style, "border-left") and contains(@style, "font-size: 17px;")]//font[1]')->item(1)->nodeValue);
$status3 = trim($xpath->query('//td[contains(@style, "border-left") and contains(@style, "font-size: 17px;")]//font[2]')->item(0)->nodeValue);
$tanggal = $xpath->query('//tr[last()]/td')->item(0)->nodeValue;


$result = array(
    'matauang' => trim($matauang),
    'deskripsi' => trim($deskripsi),
    'nilai' => trim($nilai),
    'status' => trim($status),
    'status2' => trim($status2),
    'status3' => trim($status3),
    'tanggal' => trim($tanggal)
);

$json_result = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} else {

$result = array(
    'example' => 'url-endpoint/currency.php?kurs=USD',
    'availablefor' => 'USD, SGD, AUD, EUR, CNY, HKD, GBP, JPY, CAD, NZD, MYR, THB, SAR, PHP, KRW, VND, PGK, LAK, KWD, BND'
);

$json_result = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

header('Content-Type: application/json');
print $json_result;


