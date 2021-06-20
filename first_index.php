<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
	include("$root/visit.php");
?>
<!doctype html>
<html lang="en"><!-- InstanceBegin template="/Templates/footer.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
	
	<!-- InstanceBeginEditable name="head" -->

    <link rel="stylesheet" type="text/css" href="styelsheet.css"/>
    <meta name="keywords" content="Brian Evans Technology, brianevans, computer science, programmer">
    <meta name="description" content="Welcome to the home of Brian Evans' personal website, showcasing coding examples and other interesting projects">
    <meta name="author" content="Brian Evans">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="height=device-height, initial-scale=1">
    <link href="styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet" type="text/css">
    <link href="mobile_stylesheet.css" media="only screen and (max-width: 768px)" rel="stylesheet" type="text/css">
    <link href="images/favicon.ico" rel="icon" type="image/x-icon" />
    <link rel="canonical" href="https://www.brianevans.tech" />
    <link rel="stylesheet" type="text/css" href="projects/project_gallery_styling.css"/>
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <script src="predictions/predictions.js"></script>
    <script src="frontend.js"></script>
    <title>Brian Evans</title>
</head>

<body>
		<header><a href="/" style="text-decoration: none"><h1 class="green" id="brian">Brian Evans</h1></a></header>
    <div class="notice">
        For more information on coronavirus visit <a href="https://coronavirus.gov">CoronaVirus.Gov</a>
        . For statistics, visit <a href="coronavirus">Coronavirus Statistics</a>
    </div>

		<div class="flex-container">
			<div class="single green box" style="background: #43F472" onclick="changeColor('brian', '#42F471')">
				
				<img class="logo" src="images/brianevans_logo_768px.png" alt="brian evans logo">
				
				<p class="smallhead">Welcome to my website! </p>
			  <p> I am a computer science student in the UK. <br>
				  Feel free to have a look around my website to see some of my work. <br>
				  This website was designed completely from scratch in HTML, PHP, CSS and JavaScript. Right click and hit 'View source' to see the code. </p>
                <p>To see a couple of the projects I've worked on, <a href="/about/cv" class="nolink"><button id="viewCV">View my CV</button></a></p>
            </div>

<!--            predictions plug-->
            <div class="double green box" onclick="changeColor('brian', '#42F471')">
                <h2>&nbsp; &nbsp;Predictions game</h2>
                <p>I'm running a predictions platform, where you can guess future events, and see what other people think.
                    Have a go on <a class="darker" href="predictions">/predictions</a></p>
                <strong>Guess when the next official Parkrun will take place?</strong>
                <?php
                require "predictions/predictionsModel.php";
                $tinyModel = new predictionsModel();
                $contest = array("contest_id"=>43, "contest_name"=>"When will the next parkrun take place?", "type"=>"prediction_date");
                //            $contest = array("contest_id"=>25, "contest_name"=>"Congress", "type"=>"prediction_string");
                foreach ($tinyModel->getEntriesOfContest($contest['contest_name']) as $row=>$data) {
                    $contest_details[] = $data;
                }
                unset($data);
                unset($row);
                $buttonText = $tinyModel->checkForUsernameInContest($uname, $contest['contest_name']);
                if($buttonText=="Edit entry")
                    $funcName = 'editPrediction';
                else if($buttonText=="Enter contest")
                    $funcName = 'enterPrediction';
                $previous_prediction = $tinyModel->getUsernameEntryInContest($uname, $contest["contest_name"]);
                ?>
                <div class="frontPagePredictions">
                    <div style="min-width: 90%" class="contests">
                        <table style="min-width: 100%" class="contest-entries">
                            <tr>
                                <th>Person</th>
                                <th>Date of entry</th>
                                <th>Prediction</th>
                            </tr>
                            <?php
                            foreach($contest_details as $row){
                                ?>
                                <tr>
                                    <td><?=$row['username']?></td>
                                    <td><?php if($uname=="brianevans") echo $row['datetime']; else echo date("j M", strtotime($row['datetime'])); ?></td>
                                    <td id="<?=$row['username'].$contest['contest_id']?>">
                                        <?=$row['prediction_string'] . $row['prediction_int']?>
                                        <?php if(strtotime($row['prediction_date'])) echo date("j M Y", strtotime($row['prediction_date'])) ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            unset($row);
                            ?>
                        </table>
                    </div>
                    <form class="frontPagePredictionsForm">
                        <button class="green" onclick="<?=$funcName?>(<?=$contest["contest_id"]?>)"><?=$buttonText?></button>
                        <input id="predictionEntry<?=$contest["contest_id"]?>" type="text" value="<?=$previous_prediction?>"
                               onfocus="clearBox('predictionEntry<?=$contest["contest_id"]?>')"
                               onblur="setToDefaultIfEmpty('predictionEntry<?=$contest["contest_id"]?>')"
                        style="font-size: inherit"/>
                    </form>
                </div>
            </div>


            <div class="double box orange" onclick="changeColor('brian', '#f5d142')">
                <h2>&nbsp; &nbsp;Project spotlight</h2>
                <div class="project_gallery orange">
                    <?php
                    $projects = array(
                        array("label"=>"Coronavirus Graphs", "href"=>"coronavirus", "status"=>"complete"),
                        array("label"=>"CBT App","href"=>"/projects/cbt", "status"=>"complete"),
                        array("label"=>"Investing","href"=>"/investing", "status"=>"complete"),
                        array("label"=>"A Level grades explorer","href"=>"/projects/grades-comparison", "status"=>"complete"),
                        array("label"=>"Income statistics for England","href"=>"/projects/ons/income", "status"=>"complete"),
                        array("label"=>"T9 Keypad Predictive text","href"=>"/projects/t9autocomplete", "status"=>"complete"),
                        array("label"=>"Quadcopter manager","href"=>"/projects/fpv", "status"=>"incomplete"),
                        array("label"=>"Companies House database","href"=>"/projects/companies-house/database", "status"=>"incomplete"),
                        array("label"=>"Companies House statistics","href"=>"/projects/companies-house/statistics", "status"=>"incomplete"),
                        array("label"=>"Genealogies","href"=>"/projects/historical-timeline", "status"=>"incomplete"),
                        array("label"=>"CSV to JSON Flashcards","href"=>"/projects/converters/csv-json/flashcards", "status"=>"complete"),
                        array("label"=>"Full project directory","href"=>"/projects", "status"=>"complete"),
                        array("label"=>"Encryption visualiser", "href"=>"/projects/encryption", "status"=>"incomplete")
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
		

		
		<br>
        <div class="single charcoal box" onclick="changeColor('brian', '#2F302F')">
            <a href="/about/cv" class="lightlink"><h2>Programming languages</h2></a>
            <div class="flex-container" >
                <div class="box green triple" onclick="changeColor('brian', '#42F471')">
                    <p class="smallhead">
                        Java
                    </p>
                    <p>
                        Java was the first object oriented programming language I learnt, and has become one of my favourite languages. The ability to easily create objects with attributes makes it excellent for more complicated programs.<br> Here are some of the programs I have designed in Java:
                    </p>
                    <ul class="indented">
                            <li>
                                <a href="https://github.com/mrbrianevans/hangman">Hangman game</a>
                            </li>
                            <li>
                                <a href="https://github.com/mrbrianevans/colour-palette">Colour palette</a>
                            </li>
                            <li>
                                <a href="/projects/collatz/index.php">The Collatz Conjecture</a>
                            </li>
                        </ul>
                </div>
                <div class="box green triple" onclick="changeColor('brian', '#42F471')">
                    <p class="smallhead">
                        Python
                    </p>
                I learnt Python in my university course and have really come to enjoy its simplicity from being so high level. The syntax is very intuitive, which means I can focus on the logic instead of the keywords.
                    <br>Here are some of the projects I have done in Python:
                    <ul class="indented">
                        <li>
                            <a href="https://github.com/mrbrianevans/countdown">Countdown game</a>
                        </li>
                        <li>
                            <a href="https://github.com/mrbrianevans/projectile-modeling">Projectile modelling</a>
                        </li>
                        <li>
                            <a href="https://github.com/mrbrianevans/fast-exponentiation">Faster exponentiation</a>
                        </li>
                    </ul>
                </div>
                <div class="box green triple" onclick="changeColor('brian', '#42F471')">
                    <p class="smallhead">
                    PHP
                    </p>
                    When I started this website in October 2019, I had never done PHP before.
                    I learnt PHP through taking a web development module at university and spending many hours on this website, I and have come to love it.
                    My favourites are the <span class="inlinecode num">$variable</span>'s and the 5,000 built in functions. Here are some of the pages on this website which use PHP:
                    <ul class="indented">
                        <li>
                            <a href="predictions">Predictions</a>
                        </li>
                        <li>
                            <a href="projects/cookies">Cookies</a>
                        </li>
                        <li>
                            <a href="socialmedia">Social media</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

	<footer>
			<div class="column">Connect with me on <a href="https://www.linkedin.com/in/brianevanstech">LinkedIn</a></div>

			<div class="column">Find me on <a href="https://www.behance.net/brianevanstech">Behance</a></div>

			<div class="column">Follow me on <a href="https://github.com/mrbrianevans">GitHub</a></div>
			
			<div class="blankline"> <hr> </div>
			
			<div class="column">&copy; Brian Evans 2020</div>
			
			<div class="column"><a href="https://www.brianevans.tech/sitemap.php" style="text-decoration: none">Site map</a></div>
		
			<div class="column"><a href="https://www.brianevans.tech/contact/index.php" style="text-decoration: none">Contact me</a></div>

		</footer>
</body>
</html>
