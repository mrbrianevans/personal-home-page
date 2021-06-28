<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
include("$root/visit.php");
?>
<!doctype html>
<html lang="en">
<head>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link href="/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet" type="text/css">
    <link href="/mobile_stylesheet.css" media="only screen and (max-width: 768px)" rel="stylesheet" type="text/css">
    <link href="/images/favicon.ico" rel="icon" type="image/x-icon" />
    <script type="text/javascript" src="coronavirus.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <link rel="canonical" href="/coronavirus" />
    <meta name="author" content="Brian Evans">
    <link rel="stylesheet" href="covidStyle.css" type="text/css"/>

    <title>Coronavirus</title>
    <meta name="keywords" content="China virus, chinese virus, wuhan virus, haubei virus">
    <meta name="description" content="Brian Evans wuhan virus monitoring page. I have stats and graphs showing the current situation of the virus that originated in China">

</head>

<body>
<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
<div class="notice">
    For very interesting models and statistics visit <a href="https://covid19.healthdata.org/projections">Institute for Health Metrics and Evaluation</a>
    . For predictions, visit <a href="predictions.php">Coronavirus Predictions</a>
</div>
<div class="mainbody">
    <div class="singlebox">

        <h2>&nbsp;
            COVID-19 Statistics
        </h2>
        <div class="graphGrid">
            <div id="casesAndDeathsCombined">Deaths and cases on the same graph</div>
            <div id="movingAverage"></div>
            <div id="lastTwoWeeksDaily"></div>
            <div id="rollingCaseFatalityRateUSA"></div>
            <div id="rollingCaseFatalityRateUK"></div>
            <div id="caseFatalityRate"></div>
            <div id="stateWeeklyMap"></div>
            <div id="englandWeeklyMap"></div>
            <div id="englandCOVIDproportion"></div>
            <div id="dailydeathsgraph">Graph of daily deaths loading...</div>
            <div id="deathsgraph">Graph of TOTAL deaths loading...</div>
            <div id="deathsComparison">
                <iframe height="500px" src="https://www.ons.gov.uk/visualisations/dvc1423/fig3/index.html"></iframe>
                <a class="darker"
                   href="https://www.ons.gov.uk/peoplepopulationandcommunity/birthsdeathsandmarriages/deaths/bulletins/deathsregisteredweeklyinenglandandwalesprovisional/latest"
                   target="_blank"
                >
                    View data source
                </a>
            </div>
        </div>

    </div>

</div>

<footer>
    <div class="column">Connect with me on <a href="https://www.linkedin.com/in/brianevanstech">LinkedIn</a></div>

    <div class="column">Find me on <a href="https://www.behance.net/brianevanstech">Behance</a></div>

    <div class="column">Follow me on <a href="https://github.com/mrbrianevans">GitHub</a></div>

    <div class="blankline"> <hr> </div>

    <div class="column">&copy; Brian Evans 2020</div>

    <div class="column"><a href="/sitemap.php" style="text-decoration: none">Site map</a></div>

    <div class="column"><a href="/contact/index.php" style="text-decoration: none">Contact me</a></div>

</footer>
</body></html>
