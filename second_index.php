<?php // this snippet should be on every page
    $root = $_SERVER['DOCUMENT_ROOT'];
    require("$root/visitWithoutLogin.php");
    $projects = array(
        array("label"=>"Coronavirus Graphs", "href"=>"/coronavirus", "status"=>"complete"),
        array("label"=>"Investing graphs","href"=>"/investing", "status"=>"complete"),
        array("label"=>"Predictions","href"=>"/predictions", "status"=>"complete"),
        array("label"=>"A Level grades explorer","href"=>"/projects/grades-comparison", "status"=>"complete"),
        array("label"=>"Income statistics for England","href"=>"/projects/ons/income", "status"=>"complete"),
        array("label"=>"T9 Keypad Predictive text","href"=>"/projects/t9autocomplete", "status"=>"complete"),
        array("label"=>"Companies House database","href"=>"/projects/companies-house/database", "status"=>"incomplete"),
        array("label"=>"Genealogies","href"=>"/projects/historical-timeline", "status"=>"incomplete"),
        array("label"=>"Full project directory","href"=>"/projects", "status"=>"complete"),
    );
?>
<html lang="en">
    <head>
        <title>Brian Evans Homepage</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="index/homepageStyling.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <div id="root">
        <div id="page-title">
            <h1>Brian Evans</h1>
            <p>Welcome to my website</p>
            <p>Take a look at some of my projects listed in the side bar and
                on my <a href="https://github.com/mrbrianevans">GitHub profile</a></p>
            <p>To send me a message, <a href="contact">contact me</a>. Get to know me on my <a href="about">about page</a></p>
        </div>
    </div>
    <div id="page-content">
        <p>
            What do you think of my new website design?
            I have overhauled the front page to modernise it a little bit
        </p>
        <label for="design-feedback-textarea">Let me know what you think</label>
        <textarea id="design-feedback-textarea" placeholder="This doesn't actually send anything yet"></textarea>
        <div class="align-right"><button id="send-feedback-button">Send</button></div>

    </div>
    <div class="projects-container">
        <?php foreach($projects as $project){ ?>
        <a href="<?=$project["href"]?>" class="nolink"><div class="project-box"><div><span><?=$project["label"]?></span></div></div></a>
        <?php } ?>
    </div>
    </body>
</html>
