<?php

if($_SESSION['user']=='brianevans'){
    require_once('adminModel.php');
    $model = new AdminModel();
    if(isset($_GET["chain"])){
        $sessionId = $_GET["chain"];
        $chain = $model->getChainOfSession($sessionId);
        require_once "chainView.php";
    }
    else if(isset($_GET["ip"])){
        $ip = $_GET["ip"];
        $chainList = $model->getChainListByIpAddress($ip);
        require_once "chainListView.php";
    }
    else if(isset($_GET["username"])){
        $user = $_GET["username"];
        $chainList = $model->getChainListByUsername($user);
        require_once "chainListView.php";
    }
    else if(isset($_GET["page"])){
        $page = $_GET["page"];
        $chainList = $model->getChainListByPage($page);
        require_once "chainListView.php";
    }
    else if(isset($_GET["recent"])){
        $chainList = $model->getRecentTraffic();
        require_once "chainListView.php";
    }
    else{
        $dailyVisits = $model->getDailyUniqueVisits();
        $geoVisits = $model->getLocationOfVisits();
        $ips = $model->getUniqueIPs();
        $pages = $model->getPages();
        $users = $model->getUserTraffic();
        $networkip = $model->getUniqueNetworkIPs();
        $contactmessages = $model->getContactMessages();
        arsort($ips);
        arsort($pages);
        arsort($users);
        arsort($networkip);
        require_once('navigator.php');
    }




}else echo "Sorry $uname, you do not have permission to view analytics yet. Please get in touch if you wish to see them";
