<?php

$user = $_SESSION["user"];
require_once "../../server_details.php";
$database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$sql = "SELECT * FROM quadcopters WHERE username='$user'";
$quads = $database->query($sql)->fetch_all(MYSQLI_ASSOC);

$database->close();