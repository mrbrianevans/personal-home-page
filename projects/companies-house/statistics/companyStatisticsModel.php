<?php


class companyStatisticsModel
{
    private mysqli $database;
    public function __construct()
    {
        require "../../../server_details.php";
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }
    public function __destruct()
    {
        $this->database->close();
    }
    public function companyRegistrationDates(){
        // return data for when companies were registered by year. example: 50000 companies registed in 2014
    }
    public function ageOfPersons(){
        // return data for when persons with significant control were born, such as how many were born in 1950
        $sql = "SELECT birthYear, COUNT(*) FROM psc GROUP BY birthYear";
        $results = $this->database->query($sql)->fetch_all(MYSQLI_NUM);
        $data = [];
        $currentYear = date("Y");
        foreach($results as $result){
            if($result[0]<1900 || $result[0]>$currentYear) continue;
            $data[$result[0]] = $result[1];
        }
        return $data;
    }
    public function sicCodes(){
        // return data for when persons with significant control were born, such as how many were born in 1950
    }
    public function active(){
        // return data for when persons with significant control were born, such as how many were born in 1950
    }
}