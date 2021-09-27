<?php
require "predictionsModel.php";
$pModel = new predictionsModel();

function isValidReferer(): bool
{
    $allowedHosts = ['brianevans.tech','www.brianevans.tech','localhost'];
    return in_array(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST),$allowedHosts) ;
}
function printNotAuthorisedMessage(){
    echo "HTTP REFERER: " . $_SERVER['HTTP_REFERER'];
    echo "<br>URL HOST REFERER:";
    echo parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    echo "<br>REMOTE ADDR: ";
    echo $_SERVER['REMOTE_ADDR'];
    echo "<br>USERNAME: ";
    echo $_SESSION['user'];
    echo "<br>Not authorised to create contests";
}
if(isset($_GET['newcontest'])){
    if (isValidReferer()) {
        $pModel->createContest(htmlspecialchars($_GET['newcontestname']), $_GET['newcontesttype']);
        header("Location: https://brianevans.tech/predictions");
    } else {
        printNotAuthorisedMessage();
    }
}
if (isset($_GET['newentry'])) {
    if (isValidReferer()) {
        $prediction = htmlspecialchars($_GET['prediction']);
        echo $pModel->enterContest($_GET['contestID'], $prediction, $_GET['username'], $_SERVER['REMOTE_ADDR']);
        unset($prediction);
    } else {
        printNotAuthorisedMessage();
    }
}

if (isset($_GET['edit-entry'])) {
    if (isValidReferer()) {
        $prediction = htmlspecialchars($_GET['prediction']);
        echo $pModel->editEntry($_GET['contestID'], $prediction, $_GET['username']);
        unset($prediction);
    } else {
        printNotAuthorisedMessage();
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