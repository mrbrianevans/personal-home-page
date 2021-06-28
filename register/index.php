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
		<title>Register</title>
		<meta name="keywords" content="Register, Brianevans Tech, account">
		<meta name="description" content="Register for an account on my website to access more features">
		
		<!-- InstanceEndEditable -->
	
</head>

<body>
	
	<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
		<div class="mainbody">
			<div class="singlebox">
				
				<h2>&nbsp;
					<!-- InstanceBeginEditable name="heading" -->
					<a href="../register/">Register</a>
					<!-- InstanceEndEditable -->
				</h2>
				
				<!-- InstanceBeginEditable name="content" -->
				<form action="index.php" method=POST>
					<label>Username<input type="text" name="username"></label><br/>
					<label>Password<input type="password" name="password"></label><br/>
					<label>Confirm password<input type="password" name="confirm_password"></label><br/>
					<input type="submit" name="register" value="Register">
				
				
				<?php 
					if(isset($_POST['register'])){
						$previous_page = $_SESSION["page_before_login"];
						$username = $_POST['username'];
						$password = $_POST['password'];
						$confirm_password = $_POST['confirm_password'];
						if($confirm_password == $password){
                            $database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
							echo "<br>Passwords match";
							$sql = "SELECT `username` FROM `users` WHERE `username`='$username';";
							$matches = $database->query($sql);
							$rus = mysqli_num_rows($matches);
							if($rus){
								echo "<br>User already exists. Please login via the login page";
								echo "<br><a href='login'>Login</a>";
								$database->close();
							}else{
								$username = str_replace("'", "\'", $username);
								$password = password_hash($password, PASSWORD_DEFAULT);
								$sql = "INSERT INTO `users` (`username`, `password`) VALUES('$username', '$password');";
								$database->query($sql);
								echo $database->error;
								echo "<br>Successfully registered as $username and auto-logged in";
								$_SESSION['user'] = $username;
								echo "Redirecting...";
								$database->close();
                                sleep(1);
								header("Location: $previous_page");
							}
						}else{
							echo "<br>Passwords do not match";
							echo "<br><input type='reset' name='reset' value='Start again'>";
						}
					}
				
				?></form>
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
