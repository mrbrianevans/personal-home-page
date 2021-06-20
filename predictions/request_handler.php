<?php
require "predictionsModel.php";
$pModel = new predictionsModel();
if(isset($_GET['newcontest'])){
    if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == 'brianevans.tech' or parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == 'www.brianevans.tech') {
        echo $pModel->createContest(htmlspecialchars($_GET['newcontestname']), $_GET['newcontesttype']);
        header("Location: https://brianevans.tech/predictions");
    } else {
        echo "HTTP REFERER: " . $_SERVER['HTTP_REFERER'];
        echo "\nURL HOST PARSED:";
        echo parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        echo "\nREMOTE ADDR: ";
        echo $_SERVER['REMOTE_ADDR'];
        echo "\nUSERNAME: ";
        echo $_SESSION['user'];
        echo "\nNot authorised to create contests";
    }
}
if (isset($_GET['newentry'])) {
    if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == 'brianevans.tech' or parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == 'www.brianevans.tech') {
        $prediction = htmlspecialchars($_GET['prediction']);
        echo $pModel->enterContest($_GET['contestID'], $prediction, $_GET['username'], $_SERVER['REMOTE_ADDR']);
        unset($prediction);
    } else {
        echo "HTTP REFERER: " . $_SERVER['HTTP_REFERER'];
        echo "\nURL HOST PARSED:";
        echo parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        echo "\nREMOTE ADDR: ";
        echo $_SERVER['REMOTE_ADDR'];
        echo "\nUSERNAME: ";
        echo $_SESSION['user'];
        echo "\nNot authorised to create contests";
    }
}

if (isset($_GET['edit-entry'])) {
    if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == 'brianevans.tech' or parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == 'www.brianevans.tech') {
        $prediction = htmlspecialchars($_GET['prediction']);
        echo $pModel->editEntry($_GET['contestID'], $prediction, $_GET['username']);
        unset($prediction);
    } else {
        echo "HTTP REFERER: " . $_SERVER['HTTP_REFERER'];
        echo "\nURL HOST PARSED:";
        echo parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        echo "\nREMOTE ADDR: ";
        echo $_SERVER['REMOTE_ADDR'];
        echo "\nUSERNAME: ";
        echo $_SESSION['user'];
        echo "\nNot authorised to create contests";
    }
}

if (isset($_GET['get-entry'])) {
    echo $pModel->getUsernameEntryInContest($_GET['username'], $_GET['contestName']);
}

if (isset($_POST['requested-contest'])) {
    $user = $_POST['username'];
    mail("brian@brianevans.tech", "New contest request", "$user has requested a new contest:\n".$_POST['requested-contest']);
    echo "Success";
}