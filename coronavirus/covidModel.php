<?php


class covidModel
{

    private int $latestWeek;
    private int $latestVersion;
    private int $currentYear;

    public function __construct()
    {
        //TODO: Get this programatically!!
        $this->latestWeek = 35;
        $this->latestVersion = 49;
        // the url is 'https://api.beta.ons.gov.uk/v1/datasets/weekly-deaths-age-sex/editions/covid-19/versions'
        // .then((i: { version: number }[]) => i.sort((a, b) => b.version - a.version)[0].version)
        $this->currentYear = 2021;
    }

    /** Applies Google charts weird formatting to an associative array
     * @param array $array Assoc array in the format $array[date][series] = data
     * @return string JSON encoded, Google formatted, ready to be used by DataTable()
     */
    private function convertArrayToGraphData(array $array): string
    {
        $series = array_keys($array[array_key_first($array)]);
        $graphData['cols'][] = array("label"=>"Date", "type"=>"date");
        foreach($series as $serii) $graphData["cols"][] = array("label"=>$serii, "type"=>"number");
        foreach($array as $date=>$count){
            $timestamp = $date;
            $date_formatted = 'Date(' . date("Y", $timestamp) . ', ' . (((int)date("m", $timestamp))-1) . ', ' . date("d", $timestamp) . ')' ;
            $newRow = array("c"=>array(array('v' => $date_formatted)));
            foreach($series as $serii) $newRow["c"][] = array("v"=>$count[$serii]);
            $graphData['rows'][] = $newRow;
        }
        return json_encode($graphData);
    }
    /**
     * Get the historical daily death count for the United States and United Kingdom
     * @param int $days Number of days history, ie 14 would be last two weeks. If omitted, its will get data since 1st March
     */
    public function getDeathHistory($days=null): string
    {
        $url = "https://corona.lmao.ninja/v2/historical/";
        $daysSinceMarch = (int)((time() - strtotime("1 March 2020"))/86400);
        //USA
        $usRequest = curl_init($url."usa?lastdays=".($days??$daysSinceMarch));
        curl_setopt($usRequest, 19913, 1);
        $usResponse = json_decode(curl_exec($usRequest), true);
        //UK
        $ukRequest = curl_init($url."uk?lastdays=".($days??$daysSinceMarch));
        curl_setopt($ukRequest, 19913, 1);
        $ukResponse = json_decode(curl_exec($ukRequest), true);
        $deathArray = array();
        $startDate = min(strtotime(array_key_first($usResponse["timeline"]["deaths"])), strtotime(array_key_first($ukResponse["timeline"]["deaths"])));
        $endDate = max(strtotime(array_key_last($usResponse["timeline"]["deaths"])), strtotime(array_key_last($ukResponse["timeline"]["deaths"])));
        for($timestamp=$startDate; $timestamp<=$endDate; $timestamp+=86400){
//            echo "<br>US deaths for " . date("m/d/y", $timestamp) . " are " . $usResponse["timeline"]["deaths"][date("n/j/y", $timestamp)];
            $deathArray[$timestamp]["Total deaths in United States"] = $usResponse["timeline"]["deaths"][date("n/j/y", $timestamp)];
            $deathArray[$timestamp]["Total deaths in United Kingdom"] = $ukResponse["timeline"]["deaths"][date("n/j/y", $timestamp)];
        }
//        print_r($usResponse["timeline"]["deaths"]);
        return $this->convertArrayToGraphData($deathArray);
    }

    public function getDailyDeathHistory($days=null): string
    {
        $url = "https://corona.lmao.ninja/v2/historical/";
        $daysSinceMarch = (int)((time() - strtotime("1 March 2020"))/86400);
        $usRequest = curl_init($url."usa?lastdays=".($days??$daysSinceMarch));
        curl_setopt($usRequest, 19913, 1);
        $usResponse = json_decode(curl_exec($usRequest), true);
        $ukRequest = curl_init($url."uk?lastdays=".($days??$daysSinceMarch));
        curl_setopt($ukRequest, 19913, 1);
        $ukResponse = json_decode(curl_exec($ukRequest), true);
        $deathArray = array();

        $startDate = min(strtotime(array_key_first($usResponse["timeline"]["deaths"])), strtotime(array_key_first($ukResponse["timeline"]["deaths"])));
        $endDate = max(strtotime(array_key_last($usResponse["timeline"]["deaths"])), strtotime(array_key_last($ukResponse["timeline"]["deaths"])));
        for($timestamp=$startDate; $timestamp+(7*86400)<=$endDate; $timestamp+=86400){
            $deathArray[$timestamp]["Daily deaths in United States"] = $usResponse["timeline"]["deaths"][date("n/j/y", $timestamp)];
            $deathArray[$timestamp]["Daily deaths in United Kingdom"] = $ukResponse["timeline"]["deaths"][date("n/j/y", $timestamp)];
        }
        return $this->convertArrayToGraphData($deathArray);
    }

    public function get7dayMovingAverageDeathHistory($days=null): string
    {
        $url = "https://corona.lmao.ninja/v2/historical/";
        $daysSinceMarch = (int)((time() - strtotime("1 March 2020"))/86400);
        $usRequest = curl_init($url."usa?lastdays=".($days??$daysSinceMarch));
        curl_setopt($usRequest, 19913, 1);
        $usResponse = json_decode(curl_exec($usRequest), true);
        $ukRequest = curl_init($url."uk?lastdays=".($days??$daysSinceMarch));
        curl_setopt($ukRequest, 19913, 1);
        $ukResponse = json_decode(curl_exec($ukRequest), true);
        $deathArray = array();

        $datesArray = array_keys($ukResponse["timeline"]["deaths"]);
        $startDate = min(strtotime(array_key_first($usResponse["timeline"]["deaths"])), strtotime(array_key_first($ukResponse["timeline"]["deaths"])));
        $endDate = max(strtotime(array_key_last($usResponse["timeline"]["deaths"])), strtotime(array_key_last($ukResponse["timeline"]["deaths"])));
        $timestampToDate = array_combine(array_map("strtotime", $datesArray), $datesArray);
        for($timestamp=$startDate; $timestamp+(7*86400)<=$endDate; $timestamp+=86400){
            $label = "7 day moving average of daily deaths in";
            $label = "";
            $deathArray[$timestamp+(7*86400)]["$label United States"] = round(($usResponse["timeline"]["deaths"][$timestampToDate[$timestamp+(7*86400)]]-$usResponse["timeline"]["deaths"][$timestampToDate[$timestamp]])/7);
            $deathArray[$timestamp+(7*86400)]["$label United Kingdom"] = round(($ukResponse["timeline"]["deaths"][$timestampToDate[$timestamp+(7*86400)]]-$ukResponse["timeline"]["deaths"][$timestampToDate[$timestamp]])/7);
        }
        return $this->convertArrayToGraphData($deathArray);
    }

    public function getCaseFatalityRate(): string
    {
        $url = "https://corona.lmao.ninja/v2/historical/";
        $daysSinceMarch = (int)((time() - strtotime("1 March 2020"))/86400);
        $usRequest = curl_init($url."usa?lastdays=".($days??$daysSinceMarch));
        curl_setopt($usRequest, 19913, 1);
        $usResponse = json_decode(curl_exec($usRequest), true);
        $ukRequest = curl_init($url."uk?lastdays=".($days??$daysSinceMarch));
        curl_setopt($ukRequest, 19913, 1);
        $ukResponse = json_decode(curl_exec($ukRequest), true);
        $deathArray = array();

        $datesArray = array_keys($ukResponse["timeline"]["deaths"]);
        $startDate = min(strtotime(array_key_first($usResponse["timeline"]["deaths"])), strtotime(array_key_first($ukResponse["timeline"]["deaths"])));
        $endDate = max(strtotime(array_key_last($usResponse["timeline"]["deaths"])), strtotime(array_key_last($ukResponse["timeline"]["deaths"])));
        $timestampToDate = array_combine(array_map("strtotime", $datesArray), $datesArray);
        for($timestamp=$startDate; $timestamp+(7*86400)<=$endDate; $timestamp+=86400){
            $label = "Case fatality rate in";
            $label = "";
            $deathArray[$timestamp]["$label United States"] = round(($usResponse["timeline"]["deaths"][$timestampToDate[$timestamp]]/$usResponse["timeline"]["cases"][$timestampToDate[$timestamp]]), 8);
            $deathArray[$timestamp]["$label United Kingdom"] = round(($ukResponse["timeline"]["deaths"][$timestampToDate[$timestamp]]/$ukResponse["timeline"]["cases"][$timestampToDate[$timestamp]]), 8);
        }
        return $this->convertArrayToGraphData($deathArray);
    }

    public function getWeeklyDeathsByState(){
        $url = "https://disease.sh/v3/covid-19/nyt/states";
        $statesRequest = curl_init($url);
        curl_setopt($statesRequest, 19913, 1);
        $statesResponse = json_decode(curl_exec($statesRequest), true);
        $stateTotalDeathsEachDay = array();
        foreach($statesResponse as $entry){
            $stateTotalDeathsEachDay[$entry["date"]][$entry["state"]] = $entry["deaths"];
        }
        $today = array_key_last($stateTotalDeathsEachDay);
        $sevenDaysAgo = date("Y-m-d", strtotime($today)-(7*86400));
        $graphData = array();

        foreach(array_keys($stateTotalDeathsEachDay[$today]) as $state)
            $graphData[$state] = $stateTotalDeathsEachDay[$today][$state] - $stateTotalDeathsEachDay[$sevenDaysAgo][$state];

        return json_encode($graphData);
    }

    public function getTotalWeeklyDeathsInEnglandAndWales(){
        $week = $this->latestWeek;
        $version = $this->latestVersion;
        $currentYear = $this->currentYear;
        $ageGroupURL = "https://api.beta.ons.gov.uk/v1/datasets/weekly-deaths-age-sex/editions/covid-19/versions/$version/dimensions/agegroups/options";
        $ageGroupRequest = curl_init($ageGroupURL);
        curl_setopt($ageGroupRequest, 19913, 1);
        $ageGroupResponse = curl_exec($ageGroupRequest);
        curl_close($ageGroupRequest);
        $ageGroups = json_decode($ageGroupResponse, true);
        $graphData = array();
        foreach($ageGroups["items"] as $ageGroup){
            $ageGroupLink = $ageGroup["links"]["code"]["id"];
            if($ageGroupLink === "all") continue;
            $allDeathObservationUrl = "https://api.beta.ons.gov.uk/v1/datasets/weekly-deaths-age-sex/editions/covid-19/versions/$version/observations?";
            $allDeathsQuery = "week=week-$week&agegroups=$ageGroupLink&time=$currentYear&geography=K04000001&deaths=total-registered-deaths&sex=all";
            $allDeathsRequest = curl_init($allDeathObservationUrl.str_replace("+", "%2B", $allDeathsQuery));
            curl_setopt($allDeathsRequest, 19913, 1);
            $allDeathsResponse = curl_exec($allDeathsRequest);
            curl_close($allDeathsRequest);
            $allDeaths = json_decode($allDeathsResponse, true);

            $covidDeathObservationUrl = "https://api.beta.ons.gov.uk/v1/datasets/weekly-deaths-age-sex/editions/covid-19/versions/$version/observations?";
            $covidDeathsQuery = "week=week-$week&agegroups=$ageGroupLink&time=$currentYear&geography=K04000001&deaths=deaths-involving-covid-19-registrations&sex=all";
            $covidDeathsRequest = curl_init($covidDeathObservationUrl.str_replace("+", "%2B", $covidDeathsQuery));
            curl_setopt($covidDeathsRequest, 19913, 1);
            $covidDeathsResponse = curl_exec($covidDeathsRequest);
            curl_close($covidDeathsRequest);
            $covidDeaths = json_decode($covidDeathsResponse, true);

            $allDeathsNumber = $allDeaths["observations"][0]["observation"];
            $covidDeathsNumber = $covidDeaths["observations"][0]["observation"];
            preg_match("/(^[0-9]+)/", $ageGroupLink, $lowerAgeCategory);
            $graphData[$ageGroupLink] = array("total"=>$allDeathsNumber-$covidDeathsNumber, "covid"=>$covidDeathsNumber, "lowerAge"=>$lowerAgeCategory[1]);
        }
        array_multisort(array_column($graphData, "lowerAge"), SORT_ASC, $graphData); //TODO: Make this sort by the lowerAge column!
        return json_encode($graphData);
    }

    public function getProportionOfDeathsAsCOVID()
    {
        $week = $this->latestWeek;
        $version = $this->latestVersion;
        $currentYear = $this->currentYear;
        $allDeathObservationUrl = "https://api.beta.ons.gov.uk/v1/datasets/weekly-deaths-age-sex/editions/covid-19/versions/$version/observations?".
            "week=week-$week&agegroups=all-ages&time=$currentYear&geography=K04000001&deaths=total-registered-deaths&sex=all";
        $allDeathsRequest = curl_init($allDeathObservationUrl);
        curl_setopt($allDeathsRequest, 19913, 1);
        $allDeathsResponse = curl_exec($allDeathsRequest);
        curl_close($allDeathsRequest);
        $allDeaths = json_decode($allDeathsResponse, true);

        $covidDeathObservationUrl = "https://api.beta.ons.gov.uk/v1/datasets/weekly-deaths-age-sex/editions/covid-19/versions/$version/observations?"
            ."week=week-$week&agegroups=all-ages&time=$currentYear&geography=K04000001&deaths=deaths-involving-covid-19-registrations&sex=all";
        $covidDeathsRequest = curl_init($covidDeathObservationUrl);
        curl_setopt($covidDeathsRequest, 19913, 1);
        $covidDeathsResponse = curl_exec($covidDeathsRequest);
        curl_close($covidDeathsRequest);
        $covidDeaths = json_decode($covidDeathsResponse, true);
        $allDeathsNumber = $allDeaths["observations"][0]["observation"];
        $covidDeathsNumber = $covidDeaths["observations"][0]["observation"];

        $graphData = array("covid"=>(int)$covidDeathsNumber, "other"=>(int)($allDeathsNumber-$covidDeathsNumber));
        return json_encode($graphData);
    }

    public function deleteDuplicateDatabaseEntries(){
        require "../server_details.php";
        $database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $sql = "SELECT * FROM coronavirus";
        $allEntries = $database->query($sql)->fetch_all(1);
        $newArray = [];
        foreach($allEntries as $entry){
            $date = date("Y-m-d", strtotime($entry["datetime"]));
            $newArray[$date] = $entry;
        }
        $deleteDuplicateStatement = $database->prepare("DELETE FROM coronavirus WHERE entry=?");
        foreach($allEntries as $entry){
            $date = date("Y-m-d", strtotime($entry["datetime"]));
            if($newArray[$date]!==$entry){
                $deleteDuplicateStatement->bind_param("i", $entry["entry"]);
                $deleteDuplicateStatement->execute();
            }
        }
        $deleteDuplicateStatement->close();
        $database->close();
    }
}