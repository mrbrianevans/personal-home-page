<?php
$startTime = microtime(true);
require_once "predictiveTextModel.php";
$model = new predictiveTextModel();
if (isset($_GET["word"])) {
    $model->incrementWord($_GET["word"]);
} elseif (isset($_GET["partial"])) {
    $words = $model->getWordLike($_GET["partial"]);
    require "wordsView.php";
} elseif (isset($_POST["paragraph"])) {
    $words = $model->processParagraph($_POST["paragraph"]);
    require "wordsView.php";
} elseif (isset($_GET["view"])) {
    switch ($_GET["view"]) {
        case "full":
            $words = $model->viewDatabase();
            break;
        default:
            echo "Error";
            break;
    }
    require "wordsView.php";
}else{
    require "navigator.php";
}


$timeTaken = number_format(microtime(true) - $startTime, 5);

echo "<p><sub>Request processing time: $timeTaken seconds</sub></p>";