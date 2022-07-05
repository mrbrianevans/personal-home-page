<?php
require "incomeStatsModel.php";
$incomeModel = new incomeStatsModel();

if(isset($_GET["option"])){
    switch ($_GET["option"]){
        case "list":
            echo $incomeModel->getListOfStatisticsOptions();
            break;
        default:
            echo $incomeModel->getObservation($_GET["option"]);
            break;
    }
}

function consoleLog($message){
    echo "<script>console.log('$message')</script>";
}