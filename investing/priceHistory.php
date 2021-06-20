<?php
$requestedTime = microtime(true);
require "investingModel.php";
if (isset($_GET["instrument"])) {
    $ticker = $_GET["instrument"];
    investingModel::formatHistoricalPriceData($ticker);
    if(file_exists("price_history/$ticker.json"))
        $s = 1;
}else{
    $tickers = file("top100 tickers.csv", FILE_IGNORE_NEW_LINES);
    $tickers = array_map("str_replace", array_fill(0, count($tickers), "."), array_fill(0, count($tickers), ""), $tickers);
    shuffle($tickers);
    $tickers = array_unique($tickers);
    if(isset($_GET["unique"])){
        $j = 0;
        foreach($tickers as $index=>$ticker){
            if (file_exists("price_history/$ticker.json")) {
                unset($tickers[$index]);
                $j++;
            }
        }
        echo "$j tickers already downloaded, ".count($tickers)." to go<br>";
    }

    $time_taken = [];
    $i = 0;
    $s = 0;
    foreach($tickers as $ticker){
        if($i++>=10) break;
        $start_time = microtime(true);
        investingModel::formatHistoricalPriceData($ticker);
        $time_taken[$ticker] = microtime(true) - $start_time;
        if(file_exists("price_history/$ticker.json")) {
            $s++;
            echo "$ticker downloaded in " . round($time_taken[$ticker], 2) . " seconds<br>";
        }else
            echo "$ticker failed to download<br>";
    }

}

$totalTimeTaken = microtime(true) - $requestedTime;
echo "Successfully downloaded $s tickers price history in $totalTimeTaken seconds";