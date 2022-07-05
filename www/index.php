<?php
$projects = array(
//    array("label" => "Filter Facility", "href" => "https://dev.filterfacility.co.uk", "status" => "incomplete", "description" => "A facility to filter and export companies house accounts data"),
    array("label" => "Companies Stream", "href" => "https://companies.stream", "status" => "complete", "description" => "Live stream events from companies house, such as new accounts filings."),
    array("label" => "Wiki", "href" => "https://brianevans.wiki", "status" => "complete", "description" => "My personal wiki with articles about programming, databases, and web development."),
    array("label" => "Social media data visualiser", "href" => "https://social-media-export-analyser-mrybc.ondigitalocean.app", "status" => "incomplete", "description" => "Final year dissertation project for Computer Science degree at University of Exeter."),
    array("label" => "What Whiskey", "href" => "https://whatwhiskey.com", "status" => "complete", "description" => "Data visualisation for investing in whiskey maturation."),
//    array("label" => "Predictions", "href" => "/predictions", "status" => "complete", "description" => "Make a prediction in someone else's contest, or start a new one!"),
    array("label" => "CH Guide", "href" => "https://chguide.co.uk", "status" => "complete", "description" => "A dev-friendly guide to using Companies House API."),
//    array("label" => "T9 Keypad Predictive text", "href" => "/projects/t9autocomplete", "status" => "complete", "description" => "Old fashioned 9 key number keypad for typing English words."),
    array("label" => "Full project directory", "href" => "/projects", "status" => "complete", "description" => "View a complete list of projects hosted on this website."),
);
?>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
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

    <footer><a href="contact">contact</a> | <a href="about">about</a> | <a href="https://wakatime.com/@f8dd9b3d-8b67-421e-8f3e-ab941b402e60"><img src="https://wakatime.com/badge/user/f8dd9b3d-8b67-421e-8f3e-ab941b402e60.svg?style=flat-square" alt="Total time coded since Jul 23 2020" /></a></footer>
</main>
</body>
</html>
