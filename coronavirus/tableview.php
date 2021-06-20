<?php
require "../server_details.php";
$database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sql = "SELECT datetime, uk_cases, uk_deaths, usa_cases, usa_deaths FROM coronavirus ORDER BY entry DESC LIMIT 1";
$result = $database->query($sql)->fetch_assoc();
?>
Data accurate as of <?=date("g:i a (e), D j F", strtotime($result["datetime"]))?>
<table class="coronavirus">
    <tr>
        <th></th>
        <th>UK</th>
        <th>USA</th>
    </tr>
    <tr>
        <td>Cases</td>
        <td><?=$result['uk_cases']?></td>
        <td><?=$result['usa_cases']?></td>
    </tr>
    <tr>
        <td>Deaths</td>
        <td><?=$result['uk_deaths']?></td>
        <td><?=$result['usa_deaths']?></td>
    </tr>
</table>