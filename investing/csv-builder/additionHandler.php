<?php
if (isset($_GET["folder"])) {
    $data = "\n".$_GET["instrument"].",".$_GET["price"].",".$_GET["quantity"].",".$_GET["direction"].",".$_GET["datetime"];
    file_put_contents("../fileStorage/".$_GET["folder"]."/created.csv", $data, FILE_APPEND);
}