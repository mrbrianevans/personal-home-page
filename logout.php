<?php
require_once "visit.php";
if(isset($_GET['logout'])){

    unset($_SESSION['user']);
    $uname=null;
    echo "Successfully logged out";
//    sleep(1);
    if($previous_page = $_SESSION['page_before_login'])
        header("Location: $previous_page");
    else
        header("Location: ../");
}
