<?php
$startTime = microtime(true);
function updateCoronaDatabase()
{
    $databaseTimer = microtime(true);
    $uk_data_curl = curl_init("https://corona.lmao.ninja/v2/countries/uk");
    curl_setopt($uk_data_curl, CURLOPT_RETURNTRANSFER, 1);
    $uk_data = json_decode(curl_exec($uk_data_curl));
    curl_close($uk_data_curl);
    $uk_deaths = $uk_data->deaths;
    $uk_cases = $uk_data->cases;

    $usa_data_curl = curl_init("https://corona.lmao.ninja/v2/countries/usa");
    curl_setopt($usa_data_curl, CURLOPT_RETURNTRANSFER, 1);
    $usa_data = json_decode(curl_exec($usa_data_curl));
    curl_close($usa_data_curl);
    $usa_deaths = $usa_data->deaths;
    $usa_cases = $usa_data->cases;

    require "../server_details.php";
    $conn = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);
    $conn->real_connect(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error)
        die ("Connect error: " . $conn->connect_error . " has occured<br>");

    $sql = "INSERT INTO coronavirus (`uk_cases`, `uk_deaths`, `usa_cases`, `usa_deaths`) VALUES ('$uk_cases', '$uk_deaths', '$usa_cases', '$usa_deaths')";
    if ($conn->query($sql)) {
        $timeTaken = microtime(true) - $databaseTimer;
        echo "Successfully appended to database at " . date("h:iA") . " on " . date("d M Y") . " in $timeTaken seconds <br>";
    } else {
        echo "Failed to update covid stats. Sending support email<br>";
        echo "The error that occured was ";
        print_r($conn->error_list);
        mail("brian@brianevans.tech", "Error on website", "Hi Brian, 
    \n Sorry to bother you, but the cron job has failed to update the database with covid data.
    \nThe SQL queried was $sql
    \nThe error message given was:
    \n" . $conn->error . "
    \nPlease could you look into it.
    \n
    \nThanks!
    \nMailBot");
    }

    $conn->close();
}
echo "<div>";
echo "Downloading price history: <br>";
$priceHistoryCurlObject = curl_init("https://brianevans.tech/investing/priceHistory.php");
curl_exec($priceHistoryCurlObject);
curl_close($priceHistoryCurlObject);
echo "</div>";
if (date("g")>11 or isset($_GET['mapdata'])) { // this should update at 12am and 12pm, needs testing though
    echo "<div>";
    updateCoronaDatabase();
    echo "</div>";
    echo "<div>";
    $mapDataTimer = microtime(true);
    $usa_historical_data_curl = curl_init("https://corona.lmao.ninja/v2/historical/usa?lastdays=14");
    curl_setopt($usa_historical_data_curl, CURLOPT_RETURNTRANSFER, 1);
    $usa_historical_data = curl_exec($usa_historical_data_curl);
    curl_close($usa_historical_data_curl);
    file_put_contents("data/usaHistoricalData.json", $usa_historical_data);

    $uk_historical_data_curl = curl_init("https://corona.lmao.ninja/v2/historical/uk?lastdays=14");
    curl_setopt($uk_historical_data_curl, CURLOPT_RETURNTRANSFER, 1);
    $uk_historical_data = curl_exec($uk_historical_data_curl);
    curl_close($uk_historical_data_curl);
    file_put_contents("data/ukHistoricalData.json", $uk_historical_data);

    $uk_full_historical_data_curl = curl_init("https://corona.lmao.ninja/v2/historical/uk?lastdays=99");
    curl_setopt($uk_full_historical_data_curl, CURLOPT_RETURNTRANSFER, 1);
    $uk_full_historical_data = curl_exec($uk_full_historical_data_curl);
    curl_close($uk_full_historical_data_curl);
    file_put_contents("data/ukFullHistoricalData.json", $uk_full_historical_data);

    $usa_full_historical_data_curl = curl_init("https://corona.lmao.ninja/v2/historical/usa?lastdays=99");
    curl_setopt($usa_full_historical_data_curl, CURLOPT_RETURNTRANSFER, 1);
    $usa_full_historical_data = curl_exec($usa_full_historical_data_curl);
    curl_close($usa_full_historical_data_curl);
    file_put_contents("data/usaFullHistoricalData.json", $usa_full_historical_data);

    $timeTaken = microtime(true) - $mapDataTimer;
    echo "<br>Saved historical covid data to file in $timeTaken seconds<br>";

    $weeklyMapDataRequest = curl_init("https://brianevans.tech/coronavirus/usa/request_handler.php?weekly=true");
    curl_setopt($weeklyMapDataRequest, CURLOPT_RETURNTRANSFER, 1);
    $weeklyMapData = curl_exec($weeklyMapDataRequest);
    echo "<br>weekly map data: " . $weeklyMapData;
    curl_close($weeklyMapDataRequest);
    file_put_contents("data/weeklyMap.json", $weeklyMapData);

    $map_data_curl = curl_init("usa/request_handler.php?usa-map=true");
    curl_setopt($map_data_curl, CURLOPT_RETURNTRANSFER, 1);
    $map_data = curl_exec($map_data_curl);
    curl_close($map_data_curl);
    file_put_contents("state-case-history.json", $map_data);
    unset($map_data);

    $daily_map_data_curl = curl_init("usa/request_handler.php?usa-map-daily=true");
    curl_setopt($daily_map_data_curl, CURLOPT_RETURNTRANSFER, 1);
    $map_data = curl_exec($daily_map_data_curl);
    curl_close($daily_map_data_curl);
    file_put_contents("state-new-case-history.json", $map_data);

    $timeTaken = microtime(true) - $mapDataTimer - $timeTaken;
    echo "\n<br>Map data update in $timeTaken seconds";
    echo "</div>";
}

$timeTaken = microtime(true) - $startTime;
echo "<br>Total update response served in $timeTaken seconds";