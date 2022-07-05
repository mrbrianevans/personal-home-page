<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
	include("$root/visit.php");
	$pageName = "Cookies";

	$color_cookie = "color";

	if(isset($_GET["delete"])){
					deleteCookie($color_cookie);

	}

	function deleteCookie($name){
				setcookie( $name , '', time()-30*24*60*60);
				echo "<form>Cookie succesfully deleted. No more cookies</form>";
				//echo "Current value of $name is $_COOKIE[$name]";
			}
	
	if(isset($_COOKIE[$color_cookie])){ //checks if cookies are set
		echo '<style>';
		echo "form { background-color:";
		echo $_COOKIE[$color_cookie];
		echo "; }";
		echo '</style>';
		echo "<script>";
		echo "changeColor('brian', '";
		echo $_COOKIE[$color_cookie];
		echo "');";
		echo "</script>";
		echo "<br>";
	}

	
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
	<title>
		Cookies
	</title>
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
				<div class=date>12 February 2020</div>
				<p>
					Cookies are small(&lt;4kb) files stored on a client machine, that the server can retrieve when the client browses the website. <br>
					On this page, you can set a cookie in your browser so that the next time you visit this webpage, your preferences are remembered. 
				</p>
				<p class="smallhead">
					Setting a cookie
				</p>
				
				<p>
					
					<form method="get">
						Choose a colour of your preference:<br>
						<label><input type=radio name="colour" value="#42F471">Green</label><br>
						<label><input type=radio name="colour" value="#4286F4">Blue</label><br>
						<label><input type=radio name="colour" value="#F44268">Pink</label><br>
						<button type="submit">Set cookie</button>
					</form>
				</p>
			
				<?php 
					if(isset($_GET["colour"])){
						$color = $_GET["colour"];
						echo "<p>";
						echo "Thank you for choosing a colour. This should be remembered the next time you open the website.<br>";
						setcookie($color_cookie, $color, time()+30*24*60*60);
						echo "Cookie has been set. Refresh the page to implement the change :)";
						echo "</p>";
					}
			
					echo '<style>';
					echo "form { background-color:";
					echo $_COOKIE[$color_cookie];
					echo "; }";
					echo '</style>';
					echo "<script>";
					echo "changeColor('brian', '";
					echo $_COOKIE[$color_cookie];
					echo "');";
					echo "</script>";
					echo "<br>";
					
					if(isset($_COOKIE[$color_cookie])){
						
			?>
				
			
			
			<?php
					}	
			
				?>
			<p class=smallhead>
				GDPR
			</p>
			<p>
				All cookies are stored in accordance with GDPR. By clicking "Set cookie", you consent to the use of cookies. If you wish to withdraw your consent for cookies, you can click this button: 
			</p>
			<?php
				if(isset($_COOKIE[$color_cookie])){
						
			?>
				If you wish to withdraw your consent for cookies, you can click this button: 
			<form method=GET width="50px">
				<button type="submit" name="delete" value="deleted">Delete cookie</button>
			</form>
				
			
			
			<?php
				}
				
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
