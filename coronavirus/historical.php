<?php
$date_format = "m/d/y";
if(isset($_GET["type"])){
    if($_GET["type"]=="cumulative"){
        $usa_historical_data = json_decode(file_get_contents("data/usaHistoricalData.json"));
        $usa_timeline = $usa_historical_data->timeline;
        $usa_cases = $usa_timeline->cases;
        $usa_deaths = $usa_timeline->deaths;

        $uk_historical_data = json_decode(file_get_contents("data/ukHistoricalData.json"));
        $uk_timeline = $uk_historical_data->timeline;
        $uk_cases = $uk_timeline->cases;
        $uk_deaths = $uk_timeline->deaths;

        $array['cols'][] = array("label"=>"Date", "type"=>"date");
        $array['cols'][] = array("label"=>"USA deaths", "type"=>"number");
        $array['cols'][] = array("label"=>"UK deaths", "type"=>"number");
        foreach($uk_deaths as $date=>$count){
            if ($count > 0)
                $date_formatted = 'Date(' . date("Y", strtotime($date)) . ', ' . (((int)date("m", strtotime($date)))-1) . ', ' . date("d", strtotime($date)) . ')' ;
                $array['rows'][] = array('c' => array( array('v' => $date_formatted), array('v' => $usa_deaths->$date,), array('v' => $uk_deaths->$date,)));
        }
        unset($date);
        unset($count);
        echo json_encode($array);
    }
    elseif ($_GET["type"] == "daily") {
        $usa_historical_data = json_decode(file_get_contents("data/usaHistoricalData.json"));
        $usa_timeline = $usa_historical_data->timeline;
        $usa_cases = $usa_timeline->cases;
        $usa_deaths = json_decode(json_encode($usa_timeline->deaths), true);

        $uk_historical_data = json_decode(file_get_contents("data/ukHistoricalData.json"));
        $uk_timeline = $uk_historical_data->timeline;
        $uk_cases = $uk_timeline->cases;
        $uk_deaths = json_decode(json_encode($uk_timeline->deaths), true);
        $dates = array_keys($uk_deaths);

        for ($index = count($dates)-1; $index > 0; $index--) {
            $date = $dates[$index];
            $uk_deaths[$date] = $uk_deaths[$date] - $uk_deaths[$dates[$index - 1]];
            $usa_deaths[$date] = $usa_deaths[$date] - $usa_deaths[$dates[$index - 1]];
        }
        unset($date);
        unset($index);

        unset($uk_deaths[$dates[0]]);
        unset($usa_deaths[$dates[0]]);

        $array['cols'][] = array("label"=>"Date", "type"=>"date");
        $array['cols'][] = array("label"=>"USA daily Deaths", "type"=>"number");
        $array['cols'][] = array("label"=>"UK daily Deaths", "type"=>"number");
        foreach($uk_deaths as $date=>$count){
            if ($count > 0)
                $date_formatted = 'Date(' . date("Y", strtotime($date)) . ', ' . (((int)date("m", strtotime($date)))-1) . ', ' . date("d", strtotime($date)) . ')' ;
                $array['rows'][] = array('c' => array( array('v' => $date_formatted), array('v' => $usa_deaths[$date],), array('v' => $uk_deaths[$date],)));
        }
        unset($date);
        $uk_request = curl_init("https://corona.lmao.ninja/v2/countries/uk");
        curl_setopt($uk_request, CURLOPT_RETURNTRANSFER, 1);
        $uk_data = json_decode(curl_exec($uk_request));
        $uk_today_deaths = (int) $uk_data->todayDeaths;

        $usa_request = curl_init("https://corona.lmao.ninja/v2/countries/usa");
        curl_setopt($usa_request, CURLOPT_RETURNTRANSFER, 1);
        $usa_data = json_decode(curl_exec($usa_request));
        $usa_today_deaths = (int) $usa_data->todayDeaths;

        $date = (substr($uk_data->updated, 0, 10));
        $date_formatted = 'Date(' . date("Y", ($date)) . ', ' . (((int)date("m", ($date)))-1) . ', ' . date("d", ($date)) . ')' ;
        $array['rows'][] = array('c' => array( array('v' => $date_formatted), array('v' => $usa_today_deaths,), array('v' => $uk_today_deaths,)));
        unset($date);
        unset($count);
        echo json_encode($array);
    }
    elseif ($_GET["type"] === "weekly") {
        $usa_historical_data = json_decode(file_get_contents("data/usaFullHistoricalData.json"));
        $usa_timeline = $usa_historical_data->timeline;
        $usa_deaths = json_decode(json_encode($usa_timeline->deaths), true);

        $uk_historical_data = json_decode(file_get_contents("data/ukFullHistoricalData.json"));
        $uk_timeline = $uk_historical_data->timeline;
        $uk_deaths = json_decode(json_encode($uk_timeline->deaths), true);
        $dates = array_keys($uk_deaths);
        $uk_weekly_deaths = [];
        $usa_weekly_deaths = [];
        for ($index = 0; $index < count($dates)-1; $index+=7) {
            $uk_weekly_deaths[$dates[$index]] = $uk_deaths[$dates[$index+7]] - $uk_deaths[$dates[$index]];
            $usa_weekly_deaths[$dates[$index]] = $usa_deaths[$dates[$index+7]] - $usa_deaths[$dates[$index]];
//            echo "Week starting: $dates[$index] and ending " . $dates[$index+7] . " was ".$uk_weekly_deaths[$dates[$index]]."<br>";
        }
        unset($index);
        unset($uk_weekly_deaths[$dates[0]]);
        unset($usa_weekly_deaths[$dates[0]]);

        $array['cols'][] = array("label"=>"Date", "type"=>"date");
        $array['cols'][] = array("label"=>"United States", "type"=>"number");
        $array['cols'][] = array("label"=>"United Kingdom", "type"=>"number");
        foreach($uk_weekly_deaths as $date=>$count){
            $date_formatted = 'Date(' . date("Y", strtotime($date)) . ', ' . (((int)date("m", strtotime($date)))-1) . ', ' . date("d", strtotime($date)) . ')' ;
            $array['rows'][] = array('c' => array( array('v' => $date_formatted), array('v' => $usa_weekly_deaths[$date],), array('v' => $uk_weekly_deaths[$date],)));
        }
        unset($date_formatted);


        unset($count);
        echo json_encode($array);
    }
    elseif($_GET["type"] === "moving-average"){
        require "covidModel.php";
        $covidModel = new covidModel();
        echo $covidModel->get7dayMovingAverageDeathHistory();
    } elseif($_GET["type"] === "fatality-rate"){
        require "covidModel.php";
        $covidModel = new covidModel();
        echo $covidModel->getCaseFatalityRate();
    }elseif($_GET["type"] === "state-weekly"){
        echo json_encode(json_decode(file_get_contents("data/weeklyMap.json")));
    } elseif ($_GET["type"] === "england-weekly") {
        require "covidModel.php";
        $covidModel = new covidModel();
        echo $covidModel->getTotalWeeklyDeathsInEnglandAndWales();
    } elseif($_GET["type"]=== "proportion"){
        require "covidModel.php";
        $covidModel = new covidModel();
        echo $covidModel->getProportionOfDeathsAsCOVID();
    }
}
elseif(isset($_GET["database"])){
    require "covidModel.php";
    $covidModel = new covidModel();
    $covidModel->deleteDuplicateDatabaseEntries();
}

