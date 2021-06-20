<?php
require_once "../../server_details.php";
class investingAdminModel{
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

	public function getDailyUploads(){
	    $sql = "SELECT `datetime` FROM investing ORDER BY id";
	    $allUploads = $this->database->query($sql)->fetch_all();
	    $days = [];
	    foreach ($allUploads as $upload){
	        $formattedDate = date("j M Y", strtotime($upload[0]));
	        $days[$formattedDate] += 1;
        }
	    $returnValues = [];
	    foreach($days as $day=>$uploads){
            $returnValues[$day] = array("Uploads"=>$uploads);
        }
	    return $returnValues;
    }
}