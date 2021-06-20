<?php


class messagingModel
{
    public $database;
    public function __construct(){
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->database->connect_error) {
            die("Connection failed, error code: " . $this->database->connect_error);
        }
    }
    public function __destruct(){
        $this->database->close();
    }
    public function getContacts(){
        $uname = $_SESSION['user'];
        $sql = "SELECT recipient_name FROM contacts WHERE sender_name='$uname'";
        if($contacts_list = $this->database->query($sql))
            return $contacts_list;
    }
}