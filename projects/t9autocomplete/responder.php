<?php
if (isset($_GET["typed"])) {
    $startTime = microtime(true);
     require "autocompleteModel.php";
     $predictiveModel = new autocompleteModel();
     echo $predictiveModel->predict($_GET["typed"]);
    $timeTaken = number_format(microtime(true)-$startTime, 2);
}