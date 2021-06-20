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
    <script type="text/javascript" src="predictionsTableUpdater.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <link rel="stylesheet" href="formStyling.css">
    <meta name="author" content="Brian Evans">


    <title>Coronavirus</title>
    <meta name="keywords" content="Keywords">
    <meta name="description" content="Brian Evans description">

</head>

<body onload="continuouslyUpdateStats()">
<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
<div class="notice">For more information on coronavirus visit <a href="https://coronavirus.gov">CoronaVirus.Gov</a>. For statistics, visit <a href="/coronavirus">COVID-19 statistics</a></div>
<div class="mainbody">
    <div class="singlebox">

        <h2>&nbsp;
            Coronavirus
        </h2>
        <div>
            <p class="smallhead">Unemployment predictions explaination</p>
            <img src="usa/unemployment-10-years1.png" alt="graph of ten years of unemployment in america" style="float: right; width: 50%"/>
            This contest is guessing how much of the unemployment caused by Coronavirus is long term, and how many jobs will be restored when lockdown ends.<br>

            We will do this by predicting the unemployment on 31 December 2020 as a percentage of the peak in 2020. <br>
            For example, if unemployment peaks at 70 million, and on 31 Dec 2020 it is still 70 million, then the figure is 100%. <br>
            To enter the contest, visit the <a class="darker" href="/predictions">predictions</a> page.

            <p class="smallhead">Latest</p>
            <p>
                <?php require "blsApi.php" ?>
            </p>
            <p>
                To view the data on the outdated government website, <a href="https://data.bls.gov/timeseries/LNS14000000" target="_blank"><button>click here</button></a>
            </p>
        </div>
        <div>
            <p class="smallhead">Death toll prediction</p>
            Make sure to wash hands and sing happy birthday in warm water for 20 seconds.<br>
            <table class="coronavirus">
                <tr>
                    <th>Name</th>
                    <th>UK death predictions</th>
                    <th>USA death predictions</th>
                </tr>
                <tr>
                    <td>Dad</td>
                    <td>600,000</td>
                    <td>3,000,000</td>
                </tr>
                <tr>
                    <td>Brian</td>
                    <td>50,000</td>
                    <td>15,000</td>
                </tr>
                <tr>
                    <td>Ben</td>
                    <td>300,000</td>
                    <td>100,000</td>
                </tr>
                <tr>
                    <td>Mum</td>
                    <td>500,000</td>
                    <td>1,000,000</td>
                </tr>
                <tr id="actualstatistics">
                    <td>Actual</td>
                    <td id="ukcoronavirusdeathcount"></td>
                    <td id="usacoronavirusdeathcount"></td>
                </tr>
            </table>

            <button onclick="getStoredData()">CHECK database</button>
            <span id="coronaviruslastupdated">Last updated: ...</span>
            <div id="chinavirus"></div>
        </div>


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
