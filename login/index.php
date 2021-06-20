<?php // this snippet should be on every page
	$root = $_SERVER['DOCUMENT_ROOT'];
	include("$root/visit.php");
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
		<title>Login</title>
		<meta name="keywords" content="Login, account, brianevans">
		<meta name="description" content="Login to your Brianevans Tech account to access more features of this website such as posting on social media site, and accessing analytics.">
		
		<!-- InstanceEndEditable -->
	
</head>

<body>
	
	<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
		<div class="mainbody">
			<div class="singlebox">
				
				<h2>&nbsp;
					<!-- InstanceBeginEditable name="heading" -->
					<a class="darker" href="index.php">Login</a>
					<!-- InstanceEndEditable -->
				</h2>
                <p>If you don't already have an account, you sign up here: <a class="button" href="../register/">Register</a></p>
				<!-- InstanceBeginEditable name="content" -->
				<form action="index.php" method=POST>
					<label>Username<input type="text" name="username"></label><br/>
					<label>Password<input type="password" name="password"></label><br/>
					<input type="submit" name="login" value="Login">
				</form>
				
				<?php 
					if(isset($_POST['login'])){
					    $previous_page = $_SESSION['page_before_login'];
						$username = $_POST['username'];
						$password = $_POST['password'];
						echo "Loggin in as $username";
                        $database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
						$sql = "SELECT password FROM users WHERE username='$username' LIMIT 1";
						$correct_password = $database->query($sql)->fetch_assoc()['password'];
						
						if(password_verify($password, $correct_password)){
							$_SESSION['user'] = $username;
							$uname = $username;
							$sql = "UPDATE visits SET name = '$uname' WHERE session_id='$session_id' AND name=''";
							$database->query($sql);
							$database->close();
							echo "<br>Successfully logged in as $username";
                            echo "Redirecting to $previous_page ...";
//                            sleep(1);
                            header("Location: $previous_page");
						}else{
                            $database->close();
							echo "<br>Sorry, $username, incorrect password";
						}
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
			
			<div class="column"><a href="https://www.brianevans.tech/sitemap.php" style="text-decoration: none">Site map</a></div>
		
			<div class="column"><a href="https://www.brianevans.tech/contact/index.php" style="text-decoration: none">Contact me</a></div>

		</footer>
</body>
<!-- InstanceEnd --></html>
