<?php
$ip_address = $_SERVER["REMOTE_ADDR"];
require "../server_details.php";
$database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (isset($_GET["new_contact"])) {
    if ($_SERVER['HTTP_REFERER']=="https://brianevans.tech/messaging/"||$_SERVER['HTTP_REFERER']=="/messaging/") {
        $uname = $_GET['user'];
        $recipient_name = $_GET["new_contact"];
        $sql = "INSERT INTO contacts (sender_name, sender_ip, recipient_name) VALUES ('$uname', '$ip_address', '$recipient_name')";
        if($database->query($sql)){
            echo "Successfully added $recipient_name as a contact";
        }else{
            $error = $database->error;
            if(strpos($error, "FOREIGN KEY (`recipient_name`)"))
                echo "Failed to add '$recipient_name' as a contact because that is not a registered username on our database";
            elseif(strpos($error, "FOREIGN KEY (`sender_name`) REFERENCES `users` (`username`)"))
                echo "Failed to add contact because you aren't signed in properly. Please try logging in again.";
            else
                echo "An unknown error occurred, please submit a bug fix request in the contact form: <a class='button' href='../contact'>Contact</a>";
        }
    }else{
        echo "403: Not authorised. Request referer listed as " . $_SERVER['HTTP_REFERER'];
    }
}