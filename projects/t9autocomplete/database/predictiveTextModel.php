<?php


class predictiveTextModel
{
    private mysqli $database;
    public function __construct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/server_details.php";
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }
    public function __destruct()
    {
        $this->database->close();
    }
    public function incrementWord($word){
        $word = $this->database->real_escape_string($word);
        $sql = "SELECT `usage` FROM words WHERE word='$word' LIMIT 1";
        $usage = $this->database->query($sql)->fetch_assoc()["usage"];
        if(count($usage)) {
            echo "word exists with $usage usages";
            $usage++; //increment
            $sql = "UPDATE words SET `usage`= '$usage' WHERE word='$word'";
        }
        else {
            echo "word does not exist in database";
            $keypadNumbers = self::translateLettersToNumbers($word);
            $sql = "INSERT INTO words (word, `usage`, keypad) VALUES ('$word', 1, '$keypadNumbers')";
        }
        if($this->database->query($sql)) echo "<p>Incremented $word in database</p>";
    }
    public function getWordLike($partialWord){
        $sql = "SELECT word FROM words WHERE keypad LIKE '$partialWord%' ORDER BY `usage` desc ";
        $wordArray = $this->database->query($sql)->fetch_all();
        $wordList = array_map("current", $wordArray);
        return $wordList;
    }
    public function processParagraph($paragraph){
        $paragraph = preg_replace("/([^a-z' ]+)/", " ", strtolower($paragraph));
        $words = explode(" ", $paragraph);
        $words = array_filter($words, function($word){return strlen($word);});
        foreach($words as $word) $this->incrementWord($word);
        return $words;
    }
    public static function translateLettersToNumbers($letters)
    {
        require "keypadArray.php";
        $numbers = "";
        foreach(str_split(strtolower($letters)) as $letter){
            $numbers .= $keypad[$letter];
        }
        return $numbers;
    }
    public function viewDatabase(){
        $sql = "SELECT * FROM words ORDER BY `usage` DESC";
        $response = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        echo count($response) . " words in database";
        return $response;
    }
}