<?php


class predictionsModel
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
    public function createContest($contestName, $type){
        $sql = "INSERT INTO contests (contest_name, type) VALUE ('$contestName', '$type')";
        $this->database->query($sql);
        if($this->database->error)
            return "Contest not created. This error occured: " . $this->database->error;
        else
            return "Contest '$contestName'successfully created";
    }

    public function enterContest($contest_id, $prediction, $username, $ip_address){
        $sql = "SELECT type, contest_name FROM contests WHERE contest_id='$contest_id' LIMIT 1";
        $result = $this->database->query($sql)->fetch_assoc();
        $type = $result['type'];

        if ($type === "prediction_date") {
            if(strtotime($prediction)){
                $prediction = strtotime($prediction);
                $prediction = date("Y-m-d H:i:s", $prediction);
            }
        }

        $contestName = $result['contest_name'];
        $prediction = $this->database->real_escape_string($prediction);
        $sql = "INSERT INTO predictions (contest_id, contest_name, username, ip_address, " . $type . ")  VALUES ('$contest_id', '$contestName', '$username', '$ip_address', '$prediction')";
        $this->database->query($sql);
        if($this->database->error)
            return "Entry not added. This error occurred: " . $this->database->error . "\n\nThe sql issued was $sql";
        else
            return "Entry of '$prediction' successfully added";
    }

    public function editEntry($contest_id, $prediction, $username){
        $sql = "SELECT type FROM contests WHERE contest_id='$contest_id' LIMIT 1";
        $result = $this->database->query($sql)->fetch_assoc();
        $type = $result['type'];

        if ($type === "prediction_date") {
            if(strtotime($prediction)){
                $prediction = strtotime($prediction);
                $prediction = date("Y-m-d H:i:s", $prediction);
            }
        }

        $prediction = $this->database->real_escape_string($prediction);
        $sql = "UPDATE predictions SET " . $type . "='$prediction', datetime=DEFAULT WHERE username='$username' AND contest_id='$contest_id'";
        $this->database->query($sql);
        if($this->database->error)
            return "Entry not edited. This error occured: " . $this->database->error . "\n\nThe sql issued was $sql";
        else
            return "Entry of '$prediction' successfully updated";
    }

    public function getAllContests(){
        $sql = "SELECT contest_id, contest_name, type, date_started FROM contests ORDER BY contest_id DESC";
        $results = $this->database->query($sql);
        if($this->database->error)
            return "Could not fetch contests. This error occured: " . $this->database->error;
        else
            return $results;
    }

    public function getContestsByUsername($username){
        $sql = "SELECT contest_name FROM predictions WHERE username='$username'";
        $contest_names = $this->database->query($sql)->fetch_assoc();
        $contest_names_string = "WHERE contest_name=''";
        foreach ($contest_names as $contest_name){
            $contest_names_string = $contest_names_string . " OR contest_name='$contest_name'";
        }
        $sql = "SELECT contest_name, date_started FROM contests $contest_names_string";
        $results = $this->database->query($sql);
        if($this->database->error)
            return "Could not fetch contests. This error occured: " . $this->database->error;
        else
            return $results;
    }

    public function getEntriesOfContest($contest_name){
        $sql = "SELECT type FROM contests WHERE contest_name='$contest_name' LIMIT 1";
        $result = $this->database->query($sql)->fetch_assoc();
        $type = $result['type'];

        $sql = "SELECT username, datetime, $type FROM predictions WHERE contest_name='$contest_name'";
        $results = $this->database->query($sql);
        if($this->database->error)
            return "Could not fetch contests. This error occured: " . $this->database->error . "\n\nThe sql issued was $sql";
        else
            return $results;
    }

    public function checkForUsernameInContest($username, $contest_name){
        $sql = "SELECT username FROM predictions WHERE contest_name='$contest_name' and username='$username'";
        $results = $this->database->query($sql);
        if($results->num_rows > 0)
            return "Edit entry";
        else
            return "Enter contest";
    }

    public function getUsernameEntryInContest($username, $contest_name){
        $sql = "SELECT type FROM contests WHERE contest_name='$contest_name' LIMIT 1";
        $result = $this->database->query($sql)->fetch_assoc();
        $type = $result['type'];

        $sql = "SELECT $type FROM predictions WHERE contest_name='$contest_name' and username='$username'";
        $results = $this->database->query($sql)->fetch_assoc()[$type];
        return $results;
    }
}