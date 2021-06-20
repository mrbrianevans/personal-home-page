<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
include("$root/visit.php");

?>
<!doctype html>
<html lang="en">
<head>


    <link href="https://www.brianevans.tech/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet" type="text/css">
    <link href="https://www.brianevans.tech/mobile_stylesheet.css" media="only screen and (max-width: 768px)" rel="stylesheet" type="text/css">
    <link href="https://www.brianevans.tech/images/favicon.ico" rel="icon" type="image/x-icon" />
    <script src="https://www.brianevans.tech/frontend.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <meta name="author" content="Brian Evans">


    <title>Percolation</title>
    <meta name="keywords" content="percolation, python simulation, water rock sand">
    <meta name="description" content="I used percolation to model the flow of water through a network of randomly generated rocks and simulate it in Python">

</head>

<body>
<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">
        <?php
        require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
        ?>
        In this project, I designed a simulation for water percolating (filtering) through rocks, replicating what happens when it rains.
        It represents a whole field of mathematics, and there is a constant of nature to be found. <br>
        To view the source code, visit the <a target="_blank" class="darker" href="github">GitHub repo</a> and to see an example of a simulation, watch the
        <a href="https://www.youtube.com/watch?v=y8m9elMrCrw" class="darker" target="_blank">video on YouTube</a>.
        <img src="rock-percolation-example-55percent.PNG" alt="rock percolation simulation screenshot"/>
    </div>

</div>

<footer>
    <div class="column">Connect with me on <a href="https://www.linkedin.com/in/brianevanstech">LinkedIn</a></div>

    <div class="column">Find me on <a href="https://www.behance.net/brianevanstech">Behance</a></div>

    <div class="column">Follow me on <a href="https://github.com/mrbrianevans">GitHub</a></div>

    <div class="blankline"> <hr> </div>

    <div class="column">&copy; Brian Evans 2020</div>

    <div class="column"><a href="https://www.brianevans.tech/sitemap.php" style="text-decoration: none">Site map</a></div>

    <div class="column"><a href="https://www.brianevans.tech/contact/index.php" style="text-decoration: none">Contact me</a></div>

</footer>
</body></html>
