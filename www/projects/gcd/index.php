<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
	include("$root/visit.php");
	$pageName = "Highest common factor algorithms";
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
		
		<title>Highest common factor algorithms</title>
		<meta name="keywords" content="Highest common factor, greatest common divisor, denomenator, GCD, euclid algorithm">
		<meta name="description" content="In this project, I show how to program different greatest common denomenator algorithms, for example Euclids algorithm from elements book 7.">
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
					This project was inspired by a lecturer at university, Ronaldo Menezes. </p>
				<p>
					All of the source code is freely available on GitHub using this link: <a class="button" href="https://github.com/mrbrianevans/greatest-common-divisor">View source code</a>
					
				</p>
				<p>
					The Greatest Common Divisor is the highest common factor of two numbers. The largest number that both inputs can divide by to produce an integer quotient.<br>
					There are multiple ways of finding the GCD of two numbers. This project looks at the efficiency of 3 methods.
				</p>
				<p>
					The first method I will test is Euclids algorithm. Euclid was a mathemetician 2,300 years ago, who came up with an algorithm for finding the GCD of two numbers. This algorithm was recorded in his book Elements Book 7. <br>
					This is the Python implementation of the algorithm that I came up with:
					<div class="smallscreenmessage">Sorry, this content requires a larger screen to display properly. Try turning your phone into landscape mode</div>
					<div class="code">
						<code>
							<span class="py">def</span> <span class="def">euclid</span>(x<span class="py">,</span> y): <br>
							&nbsp;&nbsp;&nbsp;&nbsp;m = <span class="func">max</span>(x<span class="py">,</span> y)<br>
							&nbsp;&nbsp;&nbsp;&nbsp;n = <span class="func">min</span>(x<span class="py">,</span> y)<br>
							&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">if</span> n == <span class="num">0</span>:<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">return</span> m <br>
							&nbsp;&nbsp;&nbsp;&nbsp;r = m % n<br>
							&nbsp;&nbsp;&nbsp;&nbsp;m = n<br>
							&nbsp;&nbsp;&nbsp;&nbsp;n = r<br>
							&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">return</span> euclid(m<span class="py">,</span> n)
						</code>
					</div>
					Here is the same algorithm, but implemented in Java:
					<div class="smallscreenmessage">Sorry, this content requires a larger screen to display properly. Try turning your phone into landscape mode</div>
					<div class="code">
						<code>
							<span class="py">private static int</span> <span class="def">euclid</span>(<span class="py">int</span> x, <span class="py">int</span> y){<br>
								&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">int</span> m <span class="py">=</span> 0;<br>
								&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">int</span> n <span class="py">=</span> 0;<br>
								&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">if</span> (x <span class="py">&lt;</span> y){<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;m <span class="py">=</span> y;<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;n <span class="py">=</span> x;<br>
								&nbsp;&nbsp;&nbsp;&nbsp;} <span class="py">else</span>{<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;m <span class="py">=</span> x;<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;n <span class="py">=</span> y;<br>
								&nbsp;&nbsp;&nbsp;&nbsp;}<br>
<br>
								&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">if</span> (n<span class="py">==</span><span class="num">0</span>){<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">return</span> m;<br>
								&nbsp;&nbsp;&nbsp;&nbsp;} <span class="py">else</span>{<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">int</span> r = m % n;<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;m <span class="py">=</span> n;<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;n <span class="py">=</span> r;<br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">return</span> euclid(m, n);<br>
								&nbsp;&nbsp;&nbsp;&nbsp;}<br>
							}<br>
						</code>
					</div>
				</p>
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
