<?php

if (isset($_GET["part"])) {
    $partName = $_GET["part"];
    $file = json_decode(file_get_contents("parts/$partName.json"));
    $typed = strtolower($_GET["typed"]);
    foreach($file as $part){
        if(($part->class==$_GET["class"]||$partName=="class") && strtolower(substr($part->name, 0, strlen($typed)))==$typed){
            echo $part->name;
            break;
        }
    }
}