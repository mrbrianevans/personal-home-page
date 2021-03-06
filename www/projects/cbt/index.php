<?php // this snippet should be on every page
include($_SERVER['DOCUMENT_ROOT'] . "/visit.php");
$pageName = "CBT App";
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
    <link href="cbtStyle.css" rel="stylesheet" type="text/css"/>

    <title><?= $pageName ?></title>
    <meta name="keywords" content="<?= $pageName ?>">
    <meta name="description" content="<?= $pageName ?>">

</head>

<body>
<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox white">

        <?php
        require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
        ?>
        <h3>App download</h3>
        <a href="app"><button>View download page</button></a>
        <hr/>
<?php
    require "controller.php";
?>
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
