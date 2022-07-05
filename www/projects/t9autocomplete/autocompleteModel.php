<?php


class autocompleteModel
{

    private $dictionary;
    private $frequencies;

    public function __construct()
    {
        $this->dictionary = json_decode(file_get_contents("numberDictionary.txt"), true);
        $this->frequencies = json_decode(file_get_contents("wordFrequency.json"), true);
    }

    public function matchAll($typed)
    {
        $predictions = [];
        $perfectMatches = [];
        foreach($this->dictionary as $letters=>$numbers){
            if($typed==$numbers)
                $perfectMatches[] = $letters;
            elseif(substr($numbers, 0, strlen($typed))==$typed)
                $predictions[] = $letters;

        }
        if(count($perfectMatches)==0)
            return $predictions;
        else
            return $perfectMatches;
    }

    public function rankWords($words){
        $wordRanks = [];
        foreach ($words as $word){
            $wordRanks[$word] = $this->frequencies[$word];
        }
        arsort($wordRanks);
        return $wordRanks;
    }

    public function predict($typed)
    {
        $predictions = $this->matchAll($typed);
        $rankedPredictions = $this->rankWords($predictions);
        $bestPrediction = array_key_first($rankedPredictions);
        return $bestPrediction;
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
}