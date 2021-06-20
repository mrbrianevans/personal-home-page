<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
	include("$root/visit.php");
	$pageName = "Projectile modeling in Python";
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/standard page.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
	
	
		
		<link href="https://www.brianevans.tech/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet" type="text/css">
		<link href="https://www.brianevans.tech/mobile_stylesheet.css" media="only screen and (max-width: 768px)" rel="stylesheet" type="text/css">
		<link href="https://www.brianevans.tech/images/favicon.ico" rel="icon" type="image/x-icon" />
		<script src="https://www.brianevans.tech/frontend.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
		<meta name="author" content="Brian Evans">
		
		
		<!-- InstanceBeginEditable name="head" -->

		<title>Projectile modeling</title>
		<meta name="keywords" content="suvat, projectile motion, simulating modeling python, matplotlib as plt">
		<meta name="description" content="Simulating projectile motion in Python using SUVAT equations, at different time intervals">
<!-- InstanceEndEditable -->
	
</head>

<body>
	
	<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
		<div class="mainbody">
			<div class="singlebox">

                <?php
                require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
                ?>
				
				<!-- InstanceBeginEditable name="content" -->
				As part of my university degree, I am learning about matrices and vectors. One application of vectors is projectile motion modelling. <br>
				I have written a script in <a class="darker" href="https://python.org">Python</a> using <a class="darker" href="https://matplotlib.org">matplotlib</a> to model the path of a projectile.<br>
				In this model, I have assumed verticle deceleration due to gravity to be <span class="nobreak">9.8ms<sup>-2</sup>,</span> and air resistance to be zero. <br>
				<p>
					The source code to this project can be found on GitHub using this link: <a class="button" href="https://mrbrianevans.github.io/projectile-modeling/">View source code</a>
				</p>
				<p>
					The first part of this project was to code the functions that resolve in the verticle and horizontal directions, to get a basic system of graphing the projectiles position after a period of time.
					Using the SUVAT system of equations, I defined a function that could give the position of the particle after a specified unit of time, given its initial projection velocity and angle. I used this function to record the position of a projectile every second, and then graph the path of the projectile. This is what that looked like:<br><br>
					<img class="graph" src="../../images/projectile/projectile70a35v1s.png" alt="graph of projectile fired at 70 &deg; at 35 meters per second">
					<br>
					This projectile was projected at an angle of <span class="nobreak">70&deg;</span> and a velocity of <span class="nobreak">35ms<sup>-2</sup></span>, and its position was noted once every second. <br>
					The theoretical maximum height of the projectile should have been 55m, but was measured to be 72.8m in this simulation. This is due to the low frequency of the measurements being recorded. The simulation overestimates the height and distance traveled of the projectile.
					<br>
					I redid this test but instead of recording the projectiles position once every second, I set it to record 100 times per second, and then graphed the results:
					<br><br><img class="graph" src="../../images/projectile/projectile70a35v001s.png" alt="graph of projectile fire at the same angle, but with more intervals of calculation for a more accurate model"><br>
					The simulation was much more accurate this time, over-estimating just <span class="nobreak">0.3%</span> from the theoretical maximum height.
				</p>
				<p>
					Next, I wrote a testing function to find the optimal angle of projection given an initial velocity to maximise distance traveled. 
					<br><br>
					<img class="graph" src="../..//images/projectile/projectile21a30v001s.png"  alt="graph of projectile fired at 21 &deg; at 30 meters per second">
					<img class="graph" src="../../images/projectile/projectile81a30v001s.png" alt="graph of projectile fired at 81 &deg; at 30 meters per second">
					<img class="graph" src="../../images/projectile/projectileALL45a30v001s.png" alt="graph of projectiles fired at an interval of 5 &deg; up to 45&deg; where the optimal distance is achieved">
					<img class="graph" src="../../images/projectile/projectileALLa30v001s.png" alt="graph of projectiles fire at an interval of 5&deg; from 1&deg; to 86&deg;"><br>
					In these 4 simulations, the projectiles initial velocity was 30ms<sup>-2</sup>. <br>
					If the angle of projection is too small, the particle will travel a sub-optimal distance as shown in the first figure, where the angle of projection was 21&deg;.<br>
					In the second figure the angle of projection was 81&deg; which also resulted in a sub-optimal distance traveled.
					The third figure shows mutliple projections at different angles, starting at 5&deg; incrementing by 5&deg; each time, and stopping at 45&deg; where the optimal angle is reached. The yellow line represents the optimal path for the projectile to follow to maximise distance traveled. <br>
					The fourth figure in this group is multiple projections, starting at 1&deg; and ending at 86&deg;, incrementing by 1 each time. <br>
					Due to the lack of air-resistance in this model, the optimal angle of projection is always 45&deg;.
				</p>
				<p class="smallhead">Whats next...</p>
				<p>
					I can improve this simulation model by adding a mu for air resistance, as well as giving the particle a mass atribute. Both of these would affect the tragectory of the particle. 
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
			
			<div class="column"><a href="https://www.brianevans.tech/sitemap.php" style="text-decoration: none">Site map</a></div>
		
			<div class="column"><a href="https://www.brianevans.tech/contact/index.php" style="text-decoration: none">Contact me</a></div>

		</footer>
</body>
<!-- InstanceEnd --></html>
