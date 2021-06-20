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
        <script src="predictions.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
        <link rel="cannonical" href="https://brianevans.tech/predictions">
        <meta name="author" content="Brian Evans">


        <title>Predictions</title>
        <meta name="keywords" content="predictions, guessing, estimating, calculating, forecasting">
        <meta name="description" content="Brian Evans predictions page is a place to predict future events such as the winners of an olympic game for example, along with many others.">

    </head>

    <body>
        <header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none">
                <h1 class="green" id="brian">
                    Brian Evans</h1></a>
        </header>
        <div class="mainbody">
            <div class="box green">

                <h2>&nbsp;<a class='darker' href="<?= $_SERVER['SCRIPT_URI'] ?>">
                    Predictions
                    </a></h2>
                <?php require "controller.php" ?>
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