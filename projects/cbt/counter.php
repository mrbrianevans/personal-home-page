<?php
if(isset($_POST["download"])){
    $data = json_decode(base64_decode($_POST["download"]));

    require_once $_SERVER['DOCUMENT_ROOT'] . "/server_details.php";
    $database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $sql = "INSERT INTO visits (date_visited, ip_address, name, page, previous, session_id)
                    VALUES ('$data->date', '$data->ip_address', '$data->name', '$data->page', '$data->previous', '$data->session')";
    $database->query($sql);
}else{
    echo "404 not found";
    header("HTTP/1.0 404 Not found");
}