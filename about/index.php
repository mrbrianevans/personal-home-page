<?php // this snippet should be on every page
	$root = $_SERVER['DOCUMENT_ROOT'];
	include("$root/visit.php");
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/standard page.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
	
	
		
		<link href="/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet" type="text/css">
		<link href="/mobile_stylesheet.css" media="only screen and (max-width: 768px)" rel="stylesheet" type="text/css">
		<link href="/images/favicon.ico" rel="icon" type="image/x-icon" />
		<script src="/frontend.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
		<meta name="author" content="Brian Evans">
		<link href="aboutPageStyles.css" rel="stylesheet"/>
		
		<!-- InstanceBeginEditable name="head" -->
	<title>About me</title>
		<!-- InstanceEndEditable -->
	
</head>

<body>
	
	<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
		<div class="mainbody">
			<div class="singlebox">
				
				<h2>&nbsp;
					<!-- InstanceBeginEditable name="heading" -->
					About me
					<!-- InstanceEndEditable -->
				</h2>
				<a href="cv" class="nolink"><button id="viewCV">View CV</button></a>
				<!-- InstanceBeginEditable name="content" -->
				<div class="flex-container" id="aboutboxes">
					
					<div class="box orange triple">
						Hi, I'm Brian Evans and I type at 95wpm.

					
						<a href="https://10fastfingers.com/typing-test/english">
							<img id="typingspeed" src="../images/typing-test_1_CR.png" alt="Typing Test" />
						</a>
						<p>Visit the <a class="darker" href="https://10fastfingers.com/typing-test/english">Typing Test</a> and try!</p>
					</div>
					<div class="box orange triple">
						<p class="smallhead">Favourite films:</p>
						<ul class="indented bulleted">
							<li>Braveheart</li>
							<li>Gladiator</li>
							<li>The Lone Survivor</li>
							<li>Gone with the Wind (1970)</li>
						</ul>
					</div>
					<div class="box orange triple">
						<p class="smallhead">Favourite music:</p>
						<ul class="indented bulleted">
							<li><b>I'll wait</b> -- <em>Kygo</em></li>
							<li><b>Oasis</b> -- <em>Kygo</em></li>
							<li><b>Happy now</b> -- <em>Kygo</em></li>
							<li><b>Stay</b> -- <em>Kygo</em></li>
						</ul>
					</div>
					<div class="box orange" id="SongOfMoses">
						<p class="smallhead">Exodus 15:2</p>
						<p>
							<span class="BibleVerse">The Lord is my strength and my song,</span><br/>
							<span class="BibleVerse">&nbsp;&nbsp;and he has become my salvation;</span><br/>
							<span class="BibleVerse">this is my God and I will praise him,</span><br/>
							<span class="BibleVerse">&nbsp;&nbsp;my fathers God, and I will exalt him.</span><br/>
							<span class="BibleVerse">The Lord is a man of war;</span><br/>
							<span class="BibleVerse">&nbsp;&nbsp;The Lord is his name.</span><br/>
						</p>
					</div>
					<div class="box orange" id="warwick">
						<img src="../images/Background.png" width="100%" min-height="300px" alt="Sunset in Warwick"/>
					</div>
					<div class="box orange single">
						More coming soon...
						<p>
						Leave any suggestions in the <a class="darker" href="/contact/index.php">contact</a> form
						</p>
					</div>
				</div>
				<!-- InstanceEndEditable -->
			</div>
			
		</div>
		
		

	<footer>
			<div class="column">Connect with me on <a href="https://www.linkedin.com/in/brianevanstech">LinkedIn</a></div>

			<div class="column">Find me on <a href="https://www.behance.net/brianevanstech">Behance</a></div>

			<div class="column">Follow me on <a href="https://github.com/mrbrianevans">GitHub</a></div>
			
			<div class="blankline"> <hr> </div>
			
			<div class="column">&copy; Brian Evans 2020</div>
			
			<div class="column"><a href="/sitemap.php" style="text-decoration: none">Site map</a></div>
		
			<div class="column"><a href="/contact/index.php" style="text-decoration: none">Contact me</a></div>

		</footer>
</body>
<!-- InstanceEnd --></html>
