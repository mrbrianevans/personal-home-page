<?php // this snippet should be on every page
include($_SERVER['DOCUMENT_ROOT'] . "/visit.php");
$pageName = "Sorting Algorithms";
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
    <link rel="stylesheet" href="canvasStyles.css">
    <script src="sortingAnimations.js"></script>
    <title><?= $pageName ?></title>
    <meta name="keywords" content="<?= $pageName ?>">
    <meta name="description" content="<?= $pageName ?>">

</head>

<body>
<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">

        <?php
        require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
        ?>
        <p class="smallhead">
            Selection sort
        </p>
        <button onclick="draw('selectionSortCanvas')">Generate objects to sort</button> <br>
        <canvas id="selectionSortCanvas" width="1000" height="300"></canvas>

        <p class="smallhead">
            Insertion sort
        </p>
        <button onclick="draw('insertionSortCanvas')">Generate objects to sort</button> <br>
        <canvas id="insertionSortCanvas" width="1000" height="300"></canvas>
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