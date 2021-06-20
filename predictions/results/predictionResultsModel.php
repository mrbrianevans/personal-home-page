<?php


class predictionResultsModel
{

    private $database;
    public function __construct(){
        $root = $_SERVER['DOCUMENT_ROOT'];
        require_once ("$root/server_details.php");
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->database->connect_error) {
            die("Connection failed, error code: " . $this->database->connect_error);
        }
    }
    public function __destruct(){
        $this->database->close();
    }

    public function getContestsWithResults()
    {
        $sql = "SELECT * FROM contests WHERE outcome_date IS NOT NULL OR outcome_int IS NOT NULL OR outcome_string IS NOT NULL";
        $contests = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        foreach ($contests as &$contest){
            $contest['predictions'] = $this->database->query("SELECT * FROM predictions WHERE contest_id=".$contest['contest_id'])->fetch_all(MYSQLI_ASSOC);

        }unset($contest);
        return $contests;
    }
}