<?php // this snippet should be on every page
	session_start();
	if(!strpos(parse_url($_SERVER['SCRIPT_URI'], PHP_URL_PATH), "login")){
        if(!strpos(parse_url($_SERVER['SCRIPT_URI'], PHP_URL_PATH), "register")){
            if(parse_url($_SERVER['SCRIPT_URI'], PHP_URL_PATH) != "/logout.php")
                $_SESSION['page_before_login'] = $_SERVER['SCRIPT_URI'];
        }
    }

    $uname = $_SESSION['user'];
	$date = date("Y-m-d H:i:s");
	$ip_address = $_SERVER["REMOTE_ADDR"];
	$page = $_SERVER['SCRIPT_URI'];
    require_once "server_details.php";
	$database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$error_message = "";
	$previous = $_SESSION['previous'];
    $session_id = session_id();
	if ($database->connect_error){
		die("Connection failed " . $database->connect_error);
	}

	$sql = "INSERT INTO visits (date_visited, ip_address, name, page, previous, session_id) 
                    VALUES ('$date', '$ip_address', '$uname', '$page', '$previous', '$session_id')";
	if ($database->query($sql) === TRUE){
        $_SESSION['previous'] = $database->insert_id;
        $sql = "SELECT visit_id FROM visits WHERE (date_visited, ip_address, name, page, previous)=('$date', '$ip_address', '$uname', '$page', '$previous') limit 1";
        $_SESSION['previous'] = $database->query($sql)->fetch_assoc()['visit_id'];
	}
	$database->close();

?>