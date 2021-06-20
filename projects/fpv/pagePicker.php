<?php
if (isset($_POST["new"])) {
    $sessionID = session_id();
    $username = $_SESSION["user"] ?? null;
    require "quadcopterModel.php";
    $newModel = new quadcopterModel($sessionID, $username, $ip_address);
    $_SESSION["quad_id"] = $newModel->getId();

    $newModel->setParts(
        $_POST["motorSize"],
        $_POST["motorKV"],
        $_POST["motorBrand"],
        $_POST["escAmps"],
        $_POST["escBrand"]
    );
    $newModel->setCharacteristics(
        $_POST["dryWeight"],
        $_POST["throttleHover"]
    );
    $errors = $newModel->getErrorList();
}
if(isset($_GET["new"])){
    require("form.php");
}
if (isset($_SESSION["quad_id"])) {
    //TODO: Retrieve data from database using quad_id and display it using a view.php
    require("retrieve.php");
    require("view.php");
}
else{
    //TODO: Show the form to input data about quad
    require("form.php");
}