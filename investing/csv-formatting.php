<?php // this snippet should be on every page
include($_SERVER['DOCUMENT_ROOT'] . "/visit.php");
?>
<!doctype html>
<html lang="en">
<head>


    <link href="/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet"
          type="text/css">
    <link href="/mobile_stylesheet.css" media="only screen and (max-width: 768px)"
          rel="stylesheet" type="text/css">
    <link href="/images/favicon.ico" rel="icon" type="image/x-icon"/>
    <script src="/frontend.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <meta name="author" content="Brian Evans">


    <title>How to format CSV file for upload</title>
    <meta name="keywords" content="csv formatting for investing, upload to portfolio performance tracker">
    <meta name="description" content="This page describes the format required by the server for the CSV files uploaded to view portfolio performance">

</head>

<body>
<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">

        <h2>&nbsp; <a class='darker' href="<?= $_SERVER['SCRIPT_URI'] ?>">
                CSV formatting for portfolio performance manager
            </a></h2>
        This is how to format your CSV so that the server can easily read your transaction history and give you the best results.
        <table class="analytics">
            <tr>
                <th>instrument</th>
                <th>quantity</th>
                <th>direction</th>
                <th>price</th>
                <th>datetime</th>
            </tr>
            <tr id="columns-example">
                <td>SNAP</td>
                <td>90</td>
                <td>buy</td>
                <td>21.43</td>
                <td>31.05.2014</td>
            </tr>
        </table>
        <br>
        When you are ready to upload, visit the main <a href="index.php">portfolio page</a>
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

    <div class="column"><a href="/sitemap.php" style="text-decoration: none">Site map</a>
    </div>

    <div class="column"><a href="/contact/index.php" style="text-decoration: none">Contact
            me</a></div>

</footer>
</body>
</html>
