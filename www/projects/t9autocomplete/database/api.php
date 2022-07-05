<?php
if (isset($_GET["typed"])) {
    $startTime = microtime(true);
    require_once "predictiveTextModel.php";
    $model = new predictiveTextModel();
    $word = $model->getWordLike($_GET["typed"])[0];
    $timeTaken = number_format(microtime(true) - $startTime, 5);
    echo $word ;
}