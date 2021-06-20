<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
require("$root/visitWithoutLogin.php");
$projects = array(
    array("label" => "Coronavirus Graphs", "href" => "/coronavirus", "status" => "complete", "description" => "Innovative ways of visualising the latest data"),
    array("label" => "Investing graphs", "href" => "/investing", "status" => "complete", "description" => "A stock market investment portfolio tracker"),
    array("label" => "Filter Facility", "href" => "https://filterfacility.co.uk", "status" => "incomplete", "description" => "A facility to filter and export companies house accounts data"),
    array("label" => "Companies Stream", "href" => "https://companies.stream", "status" => "complete", "description" => "Live stream events from companies house, such as new accounts filings"),
    array("label" => "What Whiskey", "href" => "https://whatwhiskey.com", "status" => "complete", "description" => "Data visualisation for an investment asset class, Whiskey"),
    array("label" => "Predictions", "href" => "/predictions", "status" => "complete", "description" => "Make a prediction in someone else's contest, or start a new one!"),
    array("label" => "A Level grades explorer", "href" => "/projects/grades-comparison", "status" => "complete", "description" => "Browse all A-Level results broken down by subject, gender and grade"),
//    array("label" => "Income statistics for England", "href" => "/projects/ons/income", "status" => "complete", "description" => "England income statistics by 10th percentile"),
    array("label" => "T9 Keypad Predictive text", "href" => "/projects/t9autocomplete", "status" => "complete", "description" => "Old fashioned 9 key number keypad for typing English words"),
    array("label" => "Companies House database", "href" => "/projects/companies-house/database", "status" => "incomplete", "description" => "Legacy companies house data browser"),
    array("label" => "Genealogies", "href" => "/projects/historical-timeline", "status" => "incomplete", "description" => "Biblical genealogies visualised. Search by patriarch"),
    array("label" => "Full project directory", "href" => "/projects", "status" => "complete", "description" => "View a complete list of projects hosted on this website"),
);
?>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link rel="stylesheet" type="text/css" href="index_styling.css"/>
    <title>Brian Evans</title>
</head>
<body>
<main>
    <article class="grid">

        <div id="page-title">
            <header><h1>Brian Evans</h1></header>
            <p>Welcome to my website</p>
            <p>Take a look at some of my projects listed here with CSS Grid or
                view some source code on my <a href="https://github.com/mrbrianevans">GitHub profile</a></p>
            <p>To send me a message, <a href="contact">contact me</a>. Get to know me on my <a href="about">about page</a></p>
        </div>

        <?php foreach ($projects as $project) { ?><a
                href="<?= $project["href"] ?>" class="project-link project-box-outer"
                <?= $project["href"] == "/projects" ? 'id="full-project-directory"' : "" ?>>
            <div class="project-box-inner" >

                    <h3 class="project-title"><?= $project["label"] ?></h3>
                    <p class="project-description"><?= $project["description"] ?></p>
                </div></a>
        <?php } ?>
    </article>

    <footer><a href="contact">contact</a> | <a href="about">about</a></footer>
</main>
</body>
</html>
