<?php
require_once "../server_details.php";
class AdminModel{
    private mysqli $database;
	public function __construct(){
		$this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		if ($this->database->connect_error) {
        	die("Connection failed, error code: " . $this->database->connect_error);
    	}
	}
	public function __destruct(){
		$this->database->close();
	}
	function getUniqueIPs(){
		$sql = "SELECT ip_address FROM visits;";
		
		$analytics = $this->database->query($sql);
		$unique_visitors = [];
		while($visit = $analytics->fetch_assoc()){
			if(isset($unique_visitors[$visit['ip_address']])){
				$unique_visitors[$visit['ip_address']]++;
			}else{
				$unique_visitors[$visit['ip_address']] = 1;
			}
		}
		
		return $unique_visitors;
	}
	function getUniqueNetworkIPs(){
        $sql = "SELECT ip_address FROM visits;";

        $analytics = $this->database->query($sql);
        $unique_visitors = [];

        while($visit = $analytics->fetch_assoc()){
            $ipaddr = str_replace(strrchr($visit['ip_address'], "."), "", $visit['ip_address']);
            if(isset($unique_visitors[$ipaddr])){
                $unique_visitors[$ipaddr]++;
            }else{
                $unique_visitors[$ipaddr] = 1;
            }
        }

        return $unique_visitors;
    }
    function getUserTraffic(){
        $sql = "SELECT name FROM visits;";

        $analytics = $this->database->query($sql);
        $unique_visitors = [];
        while($visit = $analytics->fetch_assoc()){
            if ($visit['name'] == "")
                $visit['name'] = "not logged in";
            if(isset($unique_visitors[$visit['name']])){
                $unique_visitors[$visit['name']]++;
            }else{
                $unique_visitors[$visit['name']] = 1;
            }
        }
        return $unique_visitors;
    }

    private function makeUrlReadable($url){
        $name = parse_url($url, PHP_URL_PATH);
        if(!strlen(pathinfo($name, PATHINFO_EXTENSION)))
            $name = pathinfo($name, PATHINFO_DIRNAME)."/".pathinfo($name, PATHINFO_FILENAME);
        else $name = pathinfo($name, PATHINFO_DIRNAME);
        $name = trim($name, "/ ");
        if(!strlen($name))
            $name = "homepage";
        return $name;
    }
	public function getPages(){
		$sql = "SELECT page FROM visits WHERE name!='brianevans';";
		$analytics = $this->database->query($sql);
		$pages = [];
		while($page = $analytics->fetch_assoc()){
            $name = $this->makeUrlReadable($page["page"]);
            $pages[$name]++;
		}
		return $pages;
	}
	function getVisitsByIP($ip){
        $sql = "SELECT page FROM visits WHERE ip_address='$ip';";
        $analytics = $this->database->query($sql);
        $pages = [];
        while($page = $analytics->fetch_assoc()){
            $name = parse_url($page['page'], PHP_URL_PATH);
            $name = str_replace("index.php", "", $name);
            if($name=="/")
                $name = "homepage";
            $name = str_replace("/", " ", $name);
            $name = trim($name);
            if(isset($pages[$name])){
                $pages[$name]++;
            }else{
                $pages[$name] = 1;
            }
        }
        return $pages;
    }

    function getContactMessages(){
        $sql = "SELECT date, ip_address, username, name, email, message FROM contact;";
        $messages = $this->database->query($sql);
        return $messages;
    }

    function getVisitsByUsername($username){
        $sql = "SELECT page FROM visits WHERE name='$username';";
        $analytics = $this->database->query($sql);
        $pages = [];
        while($page = $analytics->fetch_assoc()){
            $name = parse_url($page['page'], PHP_URL_PATH);
            $name = str_replace("index.php", "", $name);
            if($name=="/")
                $name = "homepage";
            $name = str_replace("/", " ", $name);
            $name = trim($name);
            if(isset($pages[$name])){
                $pages[$name]++;
            }else{
                $pages[$name] = 1;
            }
        }
        return $pages;
    }
    function getExactVisitsByUsername($username){
        $sql = "SELECT visit_id, date_visited, ip_address, name, page, previous, session_id FROM visits WHERE name='$username' ORDER BY visit_id DESC LIMIT 500;";
        $analytics = $this->database->query($sql);
        $pages = [];
        while($page = $analytics->fetch_assoc()){
            $name = parse_url($page['page'], PHP_URL_PATH);
            $name = str_replace("index.php", "", $name);
            if($name=="/")
                $name = "homepage";
            $name = str_replace("/", " ", $name);
            $name = trim($name);
            $page['page'] = $name;
            $pages[$page['visit_id']] = $page;
        }
        return $pages;
    }

    public function getDailyUniqueVisits()
    {
        $sql = "SELECT ip_address, date_visited FROM visits";
        $response = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        $datedVisits = array();
        foreach ($response as $visit){
            $datedVisits[date("j M Y", strtotime($visit["date_visited"]))][] = $visit["ip_address"];
        }
        $graphData = array();
        foreach($datedVisits as $date=>$ipAdresses){
            $graphData[$date] = count(array_unique($ipAdresses));
        }
        return json_encode($graphData);
    }
    public function getLocationOfVisits(){
        $sql = "SELECT ip_address, date_visited FROM visits WHERE name!='brianevans' ORDER BY visit_id DESC LIMIT 50000";
        $response = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        $locationFrequency = [];
        foreach($response as $visit){
            $record = geoip_record_by_name($visit["ip_address"]);
            $place = $record["city"];
            $place = $record["country_name"];
            $locationFrequency[$place]++;
        }
        return json_encode($locationFrequency);
    }

    public function getChainListByIpAddress($ipAddress)
    {
        $sql = "SELECT * FROM visits WHERE ip_address='$ipAddress'";
        $response = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        return $this->getChainListFromResponse($response);
    }

    public function getChainListByUsername($username){
        $sql = "SELECT * FROM visits WHERE name='$username'";
        $response = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        return $this->getChainListFromResponse($response);
    }

    public function getChainListFromResponse($response)
    {
        $sessions = [];
        foreach($response as $visit){
            if($visit["name"]=="brianevans") continue;
            $sessions[$visit["session_id"]][] = $visit;
        }
        unset($visit);
        $chainList = [];
        foreach($sessions as $sessionId=>$visits){
            $startTime = time();
            $finishTime = 0;
            $pagesVisited = [];
            $locations = [];
            $requestCount = 0;
            foreach($visits as $visit){
                $requestCount++;
                $startTime = min($startTime, strtotime($visit["date_visited"]));
                $finishTime = max($finishTime, strtotime($visit["date_visited"]));
                $pagesVisited[$this->makeUrlReadable($visit["page"])]++;
                $location = geoip_record_by_name($visits[0]["ip_address"]);
                $locations[$location["city"] . " " .$location["country_code"]]++;
            }
            arsort($locations);
            arsort($pagesVisited);
            $chainList[$finishTime] = array(
                "location"=>array_key_first($locations),
                "requests"=>$requestCount,
                "start"=>$startTime,
                "finish"=>$finishTime,
                "length"=>$finishTime-$startTime,
                "pages"=>$pagesVisited,
                "session"=>$sessionId
            );
        }
        krsort($chainList);
        return $chainList;
    }
    public function getChainOfSession($sessionId){
	    $sql = "SELECT * FROM visits WHERE session_id='$sessionId'";
	    $response = $this->database->query($sql);
        return $response;
    }
    public function getChainOfVisit($visitId){
	    // could also just get * where SessionId = sessionId
	    $chain = [];
	    $tempId = $visitId;
        do{ // adds all preceding visits
            $sql = "SELECT * FROM visits WHERE visit_id=$tempId LIMIT 1";
            $predecessor = $this->database->query($sql)->fetch_assoc();
            $chain[$predecessor["visit_id"]] = $predecessor;
            if($hasPredecessors = ($predecessor["previous"]!=0)) {
                $tempId = $predecessor["previous"];
            }
        }while($hasPredecessors);
        $tempId = $visitId;
        do{ // adds all succeeding visits
            $sql = "SELECT * FROM visits WHERE previous=$tempId LIMIT 1";
            $successor = $this->database->query($sql)->fetch_assoc();
            if($hasSuccessors = (bool) count($successor)) {
                $chain[$successor["visit_id"]] = $successor;
                $tempId = $successor["visit_id"];
            }
        }while($hasSuccessors);

        asort($chain);
        echo "<li>Date visited: " . date("j F Y", strtotime($chain[$visitId]["date_visited"])) . "</li>";
        echo "<li>IP Address:  " . $chain[$visitId]["ip_address"] . "</li>";
        echo "<li>Session ID:  " . $chain[$visitId]["session_id"] . "</li>";
        if(strlen($chain[$visitId]["name"])) echo "<li>Username:  " . $chain[$visitId]["name"] . "</li>";

        echo "<ol>";
        foreach($chain as $eachVisitId=>$visitDetails){
            echo "<li>";
            if($eachVisitId==$visitId) echo "<b>";
            echo "$eachVisitId -> " . $visitDetails["page"] . " at " . date("g:ia", strtotime($visitDetails["date_visited"]));
            if($eachVisitId==$visitId) echo "</b>";
            echo "</li>";
        }
        echo "</ol>";

    }

    public function getChainListByPage($page)
    {
        $allVisits = $this->database->query("SELECT * FROM visits")->fetch_all(MYSQLI_ASSOC);
        $matchingVisits = array_filter($allVisits, function($dbRow) use ($page) {return $this->makeUrlReadable($dbRow["page"])==$page;});
        return $this->getChainListFromResponse($matchingVisits);
    }

    public function getRecentTraffic()
    {
        $sql = "SELECT * FROM visits ORDER BY visit_id DESC LIMIT 500";
        $response = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        return $this->getChainListFromResponse($response);
    }

}