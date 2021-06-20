<?php
$seriesId = "LNS13000000";
$url = "https://api.bls.gov/publicAPI/v1/timeseries/data/";

$unemploymentRequest = curl_init($url.$seriesId);
curl_setopt($unemploymentRequest, 19913, 1);
$unemploymentResponse = json_decode(curl_exec($unemploymentRequest), true);
$highestUnemployment = 0;
foreach($unemploymentResponse["Results"]["series"][0]["data"] as $dataPoint){
    $highestUnemployment = max($highestUnemployment, $dataPoint["value"]);
}
foreach($unemploymentResponse["Results"]["series"][0]["data"] as $dataPoint){
    if($dataPoint["value"]==$highestUnemployment){
        echo "Peak unemployment reached in " . $dataPoint["periodName"] . " " . $dataPoint["year"] . " at " . number_format($dataPoint["value"]*1000);
    }
}

$latestData = $unemploymentResponse["Results"]["series"][0]["data"][0];
echo "<br>Latest released figures are for " . $latestData["periodName"];
echo " at " . number_format($latestData["value"]*1000);
$currentPredictionFigure = number_format($latestData["value"]/$highestUnemployment*100, 2);
echo "<br>Therefore the current ratio is <b>" . $currentPredictionFigure . "%</b>";