<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Testing connnection</title>
</head>
<body>

<h1>Testing connnection</h1>
<?php
require_once "server_details.php";
$database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$error_message = "";
$previous = $_SESSION['previous'];
$session_id = session_id();
if ($database->connect_error){
    echo ("Connection failed: " . $database->connect_error);
//    echo "<p>Using details: </p>";
//    echo "<li>SERVER_HOST: ".SERVER_HOST."</li>";
//    echo "<li>DB_USERNAME: ".DB_USERNAME."</li>";
//    echo "<li>DB_PASSWORD: ".DB_PASSWORD."</li>";
//    echo "<li>DB_NAME: ".DB_NAME."</li>";
}
$start = microtime();
print_r($database->query("SELECT CURRENT_TIMESTAMP() AS now;")->fetch_assoc()['now']);
$taken = microtime() - $start;
echo "<p>Queried database in $taken seconds</p>";
$database->close();
?>
</body>
</html>