<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
include("$root/visit.php");
?>
<!doctype html>
<html lang="en">
<head>


    <link href="/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet" type="text/css">
    <link href="/mobile_stylesheet.css" media="only screen and (max-width: 768px)" rel="stylesheet" type="text/css">
    <link href="/images/favicon.ico" rel="icon" type="image/x-icon" />
    <script src="/frontend.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <script src="messaging.js"></script>
    <meta name="author" content="Brian Evans">


    <title>Messaging</title>
    <meta name="keywords" content="instant messaging webapp, brianevans messaging service">
    <meta name="description" content="This is the instant messaging web app for brianevans.tech. You can create an account with a username and password and send instant messages to someone else with their username">

</head>

<body>
<header><a href="/" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">

        <h2>&nbsp;
            Messenger service
        </h2>
        <?php include_once "controller.php"?>
    </div>

</div>

<footer>
    <div class="column">Connect with me on <a href="https://www.linkedin.com/in/brianevanstech">LinkedIn</a></div>

    <div class="column">Find me on <a href="https://www.behance.net/brianevanstech">Behance</a></div>

    <div class="column">Follow me on <a href="https://github.com/mrbrianevans">GitHub</a></div>

    <div class="blankline"> <hr> </div>

    <div class="column">&copy; Brian Evans <?=date("Y")?></div>

    <div class="column"><a href="/sitemap.php" style="text-decoration: none">Site map</a></div>

    <div class="column"><a href="/contact/index.php" style="text-decoration: none">Contact me</a></div>

</footer>
</body></html>
