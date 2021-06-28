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
		
		
		<!-- InstanceBeginEditable name="head" -->
		<title>Social media site</title>
		<meta name="keywords" content="Brian Evans social media">
		<meta name="description" content="My social media site where you can post and have an account, and see other peoples posts. ">
		
		<!-- InstanceEndEditable -->
	
</head>

<body>
	
	<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
		<div class="mainbody">
			<div class="singlebox">
				
				<h2>&nbsp;
					<!-- InstanceBeginEditable name="heading" -->
					<a class="darker" href="/socialmedia/">Social media site</a>
					<!-- InstanceEndEditable -->
				</h2>
				
				<!-- InstanceBeginEditable name="content" -->
				
				<?php 
					
					require('controller.php')
				?>
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
