<?php // this snippet should be on every page
include($_SERVER['DOCUMENT_ROOT'] . "/visit.php");
$pageName = "Timeline of History";
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



    <link rel="stylesheet" href="formStyling.css" type="text/css"/>
    <link rel="stylesheet" href="generalStyling.css" type="text/css"/>

    <title><?= $pageName ?></title>
    <meta name="keywords" content="Biblical timeline, historical sequence of events">
    <meta name="description" content="In this project, I piece together a timeline of human history from different sources, starting in Genesis all the way up to the Pandemic of 2020">

</head>

<body>
<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div>
    <?php
    if(isset($modal)){
        ?>
        <div class="<?=$modal['style']?>">
            <?=$modal["content"]?>
        </div>
        <?php
    }
    ?>
<div class="mainbody">
    <div class="whitesmoke box fixed-height">

        <?php
//        require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
        ?>
        <div>
            <?php
            require "controller.php"
            ?>
        </div>


    </div>

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
