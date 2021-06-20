<?php


class csvJsonFlashcardConverterModel
{
    public static function convertFlashcardCsvToJson($csvFileName){
        $csvFile = fopen("uploads/$csvFileName.csv", "r");
        $sheetHeaders = fgetcsv($csvFile); // get those out of the way, so they aren't added to the JSON
        $array = [];
        while(!feof($csvFile)){
            $tempCard = fgetcsv($csvFile);
            $array[] = ["question"=>$tempCard[0], "answer"=>$tempCard[1]];
        }

        $json = json_encode($array);
        return $json;
    }

    public static function convertAnyCsvToJson($csvFileName){
        $csvFile = fopen("uploads/$csvFileName.csv", "r");
        $sheetHeaders = fgetcsv($csvFile);
        $array = [];
        while(!feof($csvFile)){
            $tempCard = fgetcsv($csvFile);
            $tempArray = [];
            foreach($sheetHeaders as $index=>$sheetHeader){
                $tempArray[$sheetHeader] = $tempCard[$index];
            }
            $array[] = $tempArray;
        }

        $json = json_encode($array);
        return $json;
    }
}