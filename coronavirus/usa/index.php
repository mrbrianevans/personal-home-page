<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
include("$root/visit.php");
?>
<!doctype html>
<html lang="en">
<head>


    <link href="https://www.brianevans.tech/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet"
          type="text/css">
    <link href="https://www.brianevans.tech/mobile_stylesheet.css" media="only screen and (max-width: 768px)"
          rel="stylesheet" type="text/css">
    <link href="https://www.brianevans.tech/images/favicon.ico" rel="icon" type="image/x-icon"/>
    <script src="https://www.brianevans.tech/frontend.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <meta name="author" content="Brian Evans">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="uscoronavirus.js"></script>

    <title>Lockdown map</title>
    <meta name="keywords" content="United States lockdown criteria">
    <meta name="description" content="A map of America showing how long until each state reaches the criteria to enter the phases of Opening Up America Again">

</head>

<body onload="">
<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="blue" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="notice">
        For more statistics, visit <a href="/../coronavirus">Coronavirus Statistics</a>
    </div>
    <div id="bluebox" class="single blue box">

        <h2>&nbsp;
            United States COVID Lockdown
        </h2>

        This map shows the average number of consecutive days in each state, where the number of reported COVID-19 cases has fallen.
        <div id="mapofamerica" style="width: 100%" >loading opening up map...</div>
        This map shows the number of deaths per million of population in each of the states:
        <div id="secondmapofamerica" style="width: 100%; height: 700px">loading total deaths map...</div>
        This map shows teh average number of consecutive days in each state, where the number of new COVID cases has fallen. This is an indicator that the problem is getting better.
        <div id="thirdmapofamerica" style="width: 100%; height: 700px">loading new cases decline map...</div>
        Counties with zero cases are not included in these maps, as they affect the averages and are not increasing or decreasing.
    </div>

</div>

<footer>
    <div class="column">Connect with me on <a href="https://www.linkedin.com/in/brianevanstech">LinkedIn</a></div>

    <div class="column">Find me on <a href="https://www.behance.net/brianevanstech">Behance</a></div>

    <div class="column">Follow me on <a href="https://github.com/mrbrianevans">GitHub</a></div>

    <div class="blankline">
        <hr>
    </div>

    <div class="column">&copy; Brian Evans <?= date("Y") ?></div>

    <div class="column"><a href="https://www.brianevans.tech/sitemap.php" style="text-decoration: none">Site map</a>
    </div>

    <div class="column"><a href="https://www.brianevans.tech/contact/index.php" style="text-decoration: none">Contact
            me</a></div>

</footer>
</body>
</html>
