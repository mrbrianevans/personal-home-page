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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <meta name="author" content="Brian Evans">
    <link rel="stylesheet" type="text/css" href="../projects/project_gallery_styling.css"/>

    <title>Recruitment</title>
    <meta name="keywords" content="Recruitment agency">
    <meta name="description" content="Brian Evans fake recruitement website. Dummy test site for chatbot">

</head>

<body>
<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">
        <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
        <df-messenger
                chat-icon="b539f828-f80f-4648-ac93-5c3897c42369_x.png"
                intent="WELCOME"
                chat-title="RecruitmentBot"
                agent-id="1b82dd56-6884-49b2-b1a7-9554788f46e4"
                language-code="en"
        ></df-messenger>
        <h2>&nbsp;
            Recuitment
        </h2>
        <p>
            Are you looking for a job after finishing your degree?
            We are a local recruitment agency with thousands of contacts in every industry.
            We can put you in touch with employers who need people with your skill set.
            Let us know by contacting us through the live chat feature in the bottom right of your screen.
        </p>
        <div class="single box charcoal" onclick="changeColor('brian', '#f5d142')">
            <h2>&nbsp; &nbsp;Other projects</h2>
            <div class="project_gallery pink">
                <?php
                $projects = array(
                    array("label"=>"Coronavirus Graphs", "href"=>"/coronavirus", "status"=>"complete"),
                    array("label"=>"Investing","href"=>"/investing", "status"=>"complete"),
                    array("label"=>"A Level grades explorer","href"=>"/projects/grades-comparison", "status"=>"complete"),
                    array("label"=>"Income statistics for England","href"=>"/projects/ons/income", "status"=>"incomplete"),
                    array("label"=>"T9 Keypad Predictive text","href"=>"/projects/t9autocomplete", "status"=>"incomplete"),
                    array("label"=>"Quadcopter manager","href"=>"/projects/fpv", "status"=>"incomplete"),
                    array("label"=>"Full project directory","href"=>"/projects", "status"=>"complete")
                );
                foreach($projects as $project){
                    ?>
                    <div class="project_item padded">
                        <a class="project_link" href="<?=$project["href"]?>">
                            <div class="innerLink">
                                <?=$project["label"]?>
                                <div class="status <?=$project["status"]?>">
                                    <span class="statusTooltip">
                                        Status: <?=$project["status"]?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
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

    <div class="column"><a href="https://www.brianevans.tech/sitemap.php" style="text-decoration: none">Site map</a>
    </div>

    <div class="column"><a href="https://www.brianevans.tech/contact/index.php" style="text-decoration: none">Contact
            me</a></div>

</footer>
</body>
</html>
