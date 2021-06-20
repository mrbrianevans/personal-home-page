<?php
require "../server_details.php";
$database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(isset($_GET['latest'])){
    $sql = "SELECT datetime, uk_cases, uk_deaths, usa_cases, usa_deaths FROM coronavirus ORDER BY entry DESC LIMIT 1";
    $result = $database->query($sql)->fetch_assoc();
    $database->close();
    if(isset($_GET['date']))
        echo $result['datetime'];
    else if($_GET['country']=="usa" && $_GET['stat']=="deaths")
        echo $result['usa_deaths'];
    else if($_GET['country']=="uk" && $_GET['stat']=="deaths")
        echo $result['uk_deaths'];
    else if($_GET['country']=="usa" && $_GET['stat']=="cases")
        echo $result['usa_cases'];
    else if($_GET['country']=="uk" && $_GET['stat']=="cases")
        echo $result['uk_cases'];
    else if($_GET['country']==null)
        echo json_encode($result);
}
