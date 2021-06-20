<?php
require "../server_details.php";
$database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sql = "SELECT datetime, uk_cases, uk_deaths, usa_cases, usa_deaths FROM coronavirus ORDER BY entry DESC LIMIT 1";
$result = $database->query($sql)->fetch_assoc();
?>
Data accurate as of <?=date("g:i a (e), D j F", strtotime($result["datetime"]))?>
<ul>
    <li>UK cases: <?=$result['uk_cases']?></li>
    <li>USA cases: <?=$result['usa_cases']?></li>
    <li>UK deaths: <?=$result['uk_deaths']?></li>
    <li>USA deaths: <?=$result['usa_deaths']?></li>
</ul>
