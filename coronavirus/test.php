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
    <script type="text/javascript" src="coronavirus.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">


</head>

<body onload="continuouslyUpdateStats()">
<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
<div class="notice">For more information on coronavirus visit <a href="https://coronavirus.gov">CoronaVirus.Gov</a></div>
        <h2>&nbsp;
            Coronavirus
        </h2>

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

</body></html>
