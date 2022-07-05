<?php // this snippet should be on every page
include($_SERVER['DOCUMENT_ROOT'] . "/visit.php");
$pageName = "A Level grades explorer";
if (isset($_GET["subject"])) {
    $pageDescription = "See the distribution of grades for " . urldecode($_GET["subject"]) . " in the UK. 
    Achievement statistics broken down by subject, gender and grade";
    $ogSubjects = urldecode($_GET["subject"]);
    $pageName = urldecode($_GET["subject"]) . " A Level Grades";
}else{
    $pageDescription = "Explore the latest released 2020 A Level results by subject, gender and grade achievement.";
    $ogSubjects = "all the";
}
?>
<!doctype html>
<html lang="en">
<head>


    <link href="/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet"
          type="text/css">
    <link href="/mobile_stylesheet.css" media="only screen and (max-width: 768px)"
          rel="stylesheet" type="text/css">
    <link href="gradeComparisonSpecificStyles.css" rel="stylesheet" type="text/css"/>
    <link href="/images/favicon.ico" rel="icon" type="image/x-icon"/>
    <script src="/frontend.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <meta name="author" content="Brian Evans">

    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:creator" content="@BrianEvansFour"/>
    <meta property="og:url" content="https://brianevans.tech/projects/grades-comparison"/>
    <meta property="og:title" content="Explore <?=$ogSubjects?> 2020 A Level grades"/>
    <meta property="og:description" content="An interactive explorer for <?=$ogSubjects?> 2020 A Level grades in the UK, by subject, achievement and gender"/>
    <meta property="og:image" content="https://brianevans.tech/projects/grades-comparison/mathematicsALevelGrades2020.png"/>

    <title><?= $pageName ?></title>
    <meta name="keywords" content="<?= $pageName ?>">
    <meta name="description" content="<?=$pageDescription?>">

</head>

<body>
<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">

        <?php
//        require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
        ?>
        <?php
        require "dashboardController.php" ;
        if($_SESSION["user"]=="brianevans") echo "<a href='?readPDF='>Read PDF</a>";
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
