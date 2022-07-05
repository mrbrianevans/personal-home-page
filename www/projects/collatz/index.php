<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
	include("$root/visit.php");
	$pageName = "Collatz conjecture program"
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
		
		
		<!-- InstanceBeginEditable name="head" -->
			<meta name="keywords" content="The Collatz Conjecture, Calculator, Collatz algorithm in Java">
			<meta name="description" content="A Java calculator for the Collatz Conjecture">
		  	<meta name="author" content="Brian Evans">
			<title>Collatz Conjecture</title>
		<!-- InstanceEndEditable -->
	
</head>

<body>
	
	<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
		<div class="mainbody">
			<div class="singlebox">

                <?php
                require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
                ?>
				
				<!-- InstanceBeginEditable name="content" -->
				<p>
					The Collatz Conjecture is a mathematical proposal put forward by Lothar Collatz in the 20th century. <a class="darker" href="https://en.wikipedia.org/wiki/Collatz_conjecture">[Wikipedia]</a> <br>
					The proposal is that for any starting number, n, if you repeat an algorithm enough times you will eventually get to 1.<br>
					This however cannot be shown to be true for all natural numbers. The algorithm is as follows:<br>
				</p>
				<div class="smallscreenmessage">Sorry, this content requires a larger screen to display properly. Try turning your phone into landscape mode</div>
				<div class="code" id="small">
					<code>
						<span class="py">if</span> n <span class="py">is</span> <span class="func">odd</span>: <br>
						&nbsp;&nbsp;&nbsp;&nbsp;n = <span class="num">3</span>n+<span class="num">1</span> <br>
						<span class="py">if</span> n <span class="py">is</span> <span class="func">even</span>: <br>
						&nbsp;&nbsp;&nbsp;&nbsp;n = n/<span class="num">2</span>
					</code>
				</div>
				<p>
					I have coded a Java program to calculate the path of any given natural number to 1, following this algorithm. It shows each calculation step. <br>
					You can use this tool to either test if it is true for a particular number, or to see how quickly a number converges on 1. 
				</p>
				<p>
					The entire project along with the source code is on GitHub at this link: <a class="button" href="https://github.com/mrbrianevans/collatz">Source code</a> <br>
					
				</p>
				<p>
					This is the executable .jar file which you can download and run: <a class="button" href="ColatzConjecture.jar">Download Collatz Calculator</a>
				</p>
				 <img src="the_collatz_conjecture_screenshot_java.PNG" alt="A screenshot of the Collatz Calculator running in Java">
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
