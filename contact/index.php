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
	<title>
		Contact
	</title>
	<meta name="keywords" content="Brian Evans Contact, brianevans, contact details">
	<meta name="description" content="Brian Evans' contact me page. Send me a message to get in contact">
		<!-- InstanceEndEditable -->
	
</head>

<body>
	
	<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="orange" id="brian">Brian Evans</h1></a></header>
		<div class="mainbody">
			<div class="singlebox">
				
				<h2>&nbsp;
					<!-- InstanceBeginEditable name="heading" -->
					Contact me
					<!-- InstanceEndEditable -->
				</h2>
				
				<!-- InstanceBeginEditable name="content" -->
				<?php 
				
				
					$name = "";
					$email = "";
					$message = "";
					
				
					$showForm = true;
					function removeChars($input){
						$input = htmlspecialchars($input);
						$input = stripslashes($input);
						$input = trim($input);
					
						return $input;
					}
					if(isset($error_message)){
						$errors = fopen("errors.txt", "a") or die("Operation failed");
						fwrite($errors, $error_message);
						fclose($errors);
					}
					if(isset($_POST['submit'])){
						$name = ($_POST["name"]);
						$message = $_POST["message"];
						$email = ($_POST["email"]);
						$showForm = false;
						$messages = fopen("messages.txt", "a") or die("Operation failed");
						$sql = "INSERT INTO contact (name, message, email, username, ip_address) VALUES ('$name', '$message', '$email', '$uname', '$ip_address')";
                        if ($database->query($sql) === FALSE){
                            $error_message = $database->error;
                            echo "Error: $error_message has occured";
                        }
                        mail("brian@brianevans.tech", "Message from $name", $message, "From: $email");
						$entry = "\n\nMessage from: $name (  $email  , $ip_address) on $date\n$message\n";
						fwrite($messages, $entry);
						fclose($messages);

				
				
						if ($database->connect_error){
							die("Connection failed because: " . $database->connect_error);
						}

						$sql = "UPDATE visits SET name = '$name' WHERE ip_address='$ip_address' AND name=''";

						if ($database->query($sql) === FALSE){
							$error_message = "<br>Failed to add $name entry to database due to $ip_address visit on $date. Error:" . $database->error . "<br>";
							echo($error_message);
						}
						$database->close();
					}
					if($showForm){
				?>
				
				<form id="contact" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
					<input type="text" id="name" name="name" value="Enter name" onfocus="clearBox('name')" onblur="setToDefaultIfEmpty('name')">
				
					<textarea name="message" id="message" height="200px" onfocus="clearBox('message')" onblur="setToDefaultIfEmpty('message')">Type your message here...</textarea>
				
					<input type="text" id="email" value="Enter your email address" name="email" onfocus="clearBox('email')" onblur="setToDefaultIfEmpty('email')">
					
					<input type="submit" value="Send" name="submit">
				</form>
				<?php }else{ 
						echo "Thank you ";
						echo $name;		
				 		echo " for the feedback. I will get back to you at ";
					 	echo $email;
						echo " shortly...";
						echo "<br><br>Back <a class='darker' href='index.php' style='text-decoration: none'>to contact</a>";
					}
				?>
				<p>
					This webpage collects and stores user data in accordance with GDPR. 
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
