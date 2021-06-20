<?php


class templateModel
{
    private $database;
    public function __construct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/server_details.php";
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }
    public function __destruct()
    {
        $this->database->close();
    }
}