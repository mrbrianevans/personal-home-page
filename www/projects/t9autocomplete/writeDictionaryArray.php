<?php
$start_time = microtime(true);
$words = file("words.txt", FILE_IGNORE_NEW_LINES);
$dictionary = [];
require("autocompleteModel.php");
foreach($words as $word){
    $dictionary[trim($word)] = autocompleteModel::translateLettersToNumbers(trim($word));
}
file_put_contents("numberDictionary.json", json_encode($dictionary));
$time_taken = number_format(microtime(true)-$start_time, 2);
echo "Finished writing file in $time_taken seconds";