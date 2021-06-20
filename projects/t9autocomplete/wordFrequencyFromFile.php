<?php
if (isset($_GET["file"])) {
    $fileName = $_GET["file"];

    $start_time = microtime(true);
    $text = file_get_contents("$fileName.txt");

//remove foul characters
    {
        $text = str_replace(".", " ", $text);
        $text = str_replace("-", " ", $text);
        $text = str_replace("\"", " ", $text);
        $text = str_replace("'", " ", $text);
        $text = str_replace(";", " ", $text);
        $text = str_replace("!", " ", $text);
        $text = str_replace("\n", " ", $text);
        $text = str_replace("\r", " ", $text);
        $text = str_replace(",", " ", $text);
        $text = str_replace("â€™", " ", $text);
        $text = str_replace("â€”", " ", $text);
        $text = str_replace("”", " ", $text);
        $text = str_replace("â€", " ", $text);
        $text = str_replace("œ", " ", $text);
        $text = str_replace("â€", " ", $text);
        $text = str_replace("“", " ", $text);
        $text = str_replace("_", " ", $text);
        $text = str_replace("—", " ", $text);
        $text = str_replace("’", " ", $text);
        $text = str_replace("[", " ", $text);
        $text = str_replace("]", " ", $text);
        $text = str_replace("{", " ", $text);
        $text = str_replace("}", " ", $text);
        $text = str_replace("(", " ", $text);
        $text = str_replace(")", " ", $text);
        $text = str_replace("/", " ", $text);
        $text = str_replace("|", " ", $text);
        $text = str_replace(":", " ", $text);
        $text = str_replace("?", " ", $text);
        $text = str_replace("‘", " ", $text);
        $text = str_replace("˜", " ", $text);
        $text = str_replace("=", " ", $text);
        $text = str_replace("–", " ", $text);
        $text = str_replace(">", " ", $text);
        $text = str_replace("<", " ", $text);
        $text = str_replace("~", " ", $text);
        $text = str_replace("\\", " ", $text);
        $text = str_replace("%", " ", $text);
        $text = str_replace("$", " ", $text);
        $text = str_replace("*", " ", $text);
        $text = str_replace("&", " ", $text);
        $text = str_replace("^", " ", $text);
        $text = str_replace("@", " ", $text);
        $text = str_replace("$", " ", $text);
        $text = str_replace("#", " ", $text);
    }
//put into array
    $arrayOfWords = explode(" ", $text);

//load words from file
    $wordFrequencies = json_decode(file_get_contents("wordFrequency.json"), true);

    $recognisedWords = 0;
    $unrecognisedWordCount = 0;
    $lowercaseMatches = 0;
    $wordcaseMatches = 0;
    $addedWords = [];
    foreach($arrayOfWords as $word){
        $trimmedWord = trim($word);
        if(strlen($trimmedWord)===0) continue;
        if(isset($wordFrequencies[$trimmedWord])) {
            $wordFrequencies[$trimmedWord]++;
            $recognisedWords++;
        }elseif(isset($wordFrequencies[strtolower($trimmedWord)])){
            $wordFrequencies[strtolower($trimmedWord)]++;
            $lowercaseMatches++;
        }elseif(isset($wordFrequencies[ucwords($trimmedWord)])){
            $wordFrequencies[ucwords($trimmedWord)]++;
            $wordcaseMatches++;
        }
        else {
            if(preg_match("/\\d/", $trimmedWord) == 0) {
                $addedWords[$trimmedWord]++;
            }
            $unrecognisedWordCount++;
        }
    }
    unset($word);
    foreach($addedWords as $addedWord=>$occurances){
        if($occurances < 5) unset($addedWords[$addedWord]);
    }
    unset($addedWord);
    unset($occurances);
    $wordFrequencies = $wordFrequencies + $addedWords;
    file_put_contents("wordFrequency.json", json_encode($wordFrequencies));

    $time_taken = number_format(microtime(true)-$start_time, 2);
    echo "Finished writing file in $time_taken seconds. <br>";
    echo "$recognisedWords words were recognised, and $unrecognisedWordCount were not already registered<br>";
    echo "$lowercaseMatches words have been made lower case, and $wordcaseMatches words have been made sentence case<br>";
    echo count($addedWords) . " words were added<br>";
    echo "These are the words which were added: <br>";
    arsort($addedWords);
    echo "<table><tr><th>Word</th><th>Occurances</th></tr>";
    foreach($addedWords as $addedWord=>$occurances){
        echo "<tr><td>$addedWord</td><td>$occurances</td></tr>";
    }
    echo "</table>";
}
elseif (isset($_GET["reset"])) {
    $start_time = microtime(true);
    $allWords = file("words.txt", FILE_IGNORE_NEW_LINES);
    $wordFrequencies = array_fill_keys($allWords, 0);

    file_put_contents("wordFrequency.json", json_encode($wordFrequencies));

    $time_taken = number_format(microtime(true)-$start_time, 2);
    echo "Finished erasing file in $time_taken seconds";
} elseif (isset($_GET["xml"])) {
    $start_time = microtime(true);
    $filename = strtoupper($_GET["xml"]);
    $folder = strtoupper(substr($filename, 0, 1));
    $subfolder = strtoupper(substr($filename, 0, 2));
    $fileLocation = "corpora/oxford/download/Texts/$folder/$subfolder/$filename.xml";
    echo $fileLocation;
    $xmlFile = file_get_contents($fileLocation);
    preg_match_all("/hw=\"([^\"]*)\"/", $xmlFile, $words);
    $arrayOfWords = $words[1];

    $wordFrequencies = json_decode(file_get_contents("wordFrequency.json"), true);

    $recognisedWords = 0;
    $unrecognisedWordCount = 0;
    $lowercaseMatches = 0;
    $wordcaseMatches = 0;
    $addedWords = [];
    foreach($arrayOfWords as $word){
        $trimmedWord = trim($word);
        if(strlen($trimmedWord)===0) continue;
        if(isset($wordFrequencies[$trimmedWord])) {
            $wordFrequencies[$trimmedWord]++;
            $recognisedWords++;
        }elseif(isset($wordFrequencies[strtolower($trimmedWord)])){
            $wordFrequencies[strtolower($trimmedWord)]++;
            $lowercaseMatches++;
        }elseif(isset($wordFrequencies[ucwords($trimmedWord)])){
            $wordFrequencies[ucwords($trimmedWord)]++;
            $wordcaseMatches++;
        }
        else {
            if(preg_match("/[a-z]+/", $trimmedWord) == 1) {
                $addedWords[$trimmedWord]++;
            }
            $unrecognisedWordCount++;
        }
    }
    unset($word);
    foreach($addedWords as $addedWord=>$occurances){
        if($occurances < 5) unset($addedWords[$addedWord]);
    }
    unset($addedWord);
    unset($occurances);
    $wordFrequencies = $wordFrequencies + $addedWords;
    file_put_contents("wordFrequency.json", json_encode($wordFrequencies));
    $time_taken = number_format(microtime(true)-$start_time, 2);
    echo "Finished writing file in $time_taken seconds. <br>";
    echo "$recognisedWords words were recognised, and $unrecognisedWordCount were not already registered<br>";
    echo "$lowercaseMatches words have been made lower case, and $wordcaseMatches words have been made sentence case<br>";
    echo count($addedWords) . " words were added<br>";
    echo "These are the words which were added: <br>";
    arsort($addedWords);
    echo "<table><tr><th>Word</th><th>Occurances</th></tr>";
    foreach($addedWords as $addedWord=>$occurances){
        echo "<tr><td>$addedWord</td><td>$occurances</td></tr>";
    }
    echo "</table>";
} elseif (isset($_GET["files"])) {
    $listOfFiles = scandir("corpora/oxford/download/Texts");
    print_r($listOfFiles);
}