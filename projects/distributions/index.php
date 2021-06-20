<?php // this snippet should be on every page
include($_SERVER['DOCUMENT_ROOT'] . "/visit.php");
$pageName = "Distributions";
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


    <title>Distributions</title>
    <meta name="keywords" content="Keywords">
    <meta name="description" content="Brian Evans description">

</head>

<body>
<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">

        <?php
        require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
        ?>
There are a couple types of distributions: normal (Gaussian), binomial, geometric, poisson and more.
        In this project, I will attempt to make a tool which allows the user to graph different points on different types of distributions to model real life scenarios.
        Starting with the normal distribution of heights in the human population.

        <p class="smallhead">Heights</p>
        <p>
            Through many studies, human heights in the United Kingdom have been found to center around 5'10 for males,
            and 5'5 for females, with a standard deviation of 3 inches. This can be modeled with a normal distribution.
        </p>

        Random number:
        <?php require "controller.php" ?>

        <p>
            Unfortunately I was unable to calculate values in the normal distribution using PHP, so this project is dormant for the time being.
        </p>
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
