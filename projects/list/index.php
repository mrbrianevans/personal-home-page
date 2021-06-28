<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
	include("$root/visit.php");
	$pageName = "Creating list objects in Java";
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
	<title>Lists in Java</title>
	<meta name="keywords" content="Java list object, increase array size in java">
	<meta name="description" content="In this project, I show how you can make a list object in Java by doubling the size of an array. This can be useful if you don't know how many elements you need to store.">
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
				<div class="date">11 February 2020</div>
				
				<p>
					Using arrays, it is possible to create the illusion of a list in Java. <br>
					Arrays have a fixed size, while list length can be changed after initialisation. Once an array has been created, you cannot add items to it, which makes it not so good for some applications, where the size required is not certain at the time of creation. <br>
					Typically, accessing items in a list is sequential, meaning you have to traverse the whole list from the start until the item you want to access. On large lists this can be very innefficient. By simulating a list using arrays, you can avoid this problem, and have direct access to all elements, making retreival faster. <br>
					The downside of this method, is that it will periodically need to copy the entire list to a new array.<br>
					Here is my implementation in Java:
				</p>
				<div class="smallscreenmessage">Sorry, this content requires a larger screen to display properly. Try turning your phone into landscape mode</div>
				<div class="code">
					<code>
						<span class="py">public class</span> <span>List</span> { <br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class=py>int</span> [] <span class=func>arr</span> = <span class=py>new int</span> [<span class=num>8</span>]<span class=py>;</span> <span class=comment> //start with array size 8</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class=py>int</span> <span class=func>items</span> = <span class=num>0</span><span class=py>;</span><span class=comment> //counter of list items</span><br>
						}
					</code>
				</div>
				<p>
					This is the general structure of the List object. Just two components: an array to store the values, and a counter for how many items are in the list. <br>
					In this example, I have made it a list of integers, but this could also be done for a different data type such as String.
                    However it is important to note that this implementation is homogeneous meaning all elements of the list must be the same datatype. <br>
					To add elements to the list, and scale the array accordingly, I have written this method:
				</p>
				<div class="mediumscreenmessage">Sorry, this piece of code requires a larger browser window to display correctly.</div>
				<div class="code large">
					<code>
						<span class="py">public void</span> <span class=def>add</span>(<span class="py">int</span> value) { <br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class=py>if</span> (items <span class=py>>=</span> <span class=func>arr.length</span>){<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">int</span> [] tempArr <span class=py>= new int</span>[<span class=func>arr.length</span>*<span class=num>2</span>]<span class=py>;</span><span class=comment> //create a new array, double the size</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;System.<em>arraycopy</em>(<span class=func>arr</span><span class=py>, </span><span class=num>0</span><span class=py>, </span>tempArr<span class=py>, </span><span class=num>0</span><span class=py>, </span><span class=func>arr.length</span>)<span class=py>;</span> <span class=comment> //copy arr to tempArr</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=func>arr</span> = tempArr<span class=py>;</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;}<br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class=func>arr</span>[<span class=func>items</span>++] = value<span class=py>;</span><span class=comment> //increment items counter and assign value</span><br>
						}
					</code>
				</div>
				<p>
					The object starts with an array of size 8, and if a 9th element is added to the "List", then the <span class="inlinecode"><code><span class="def">add</span></code></span> method will create a new array of double the size (16 in this case), and copy all the elements over to it. This copy procedure will slow down the operation, but will not happen very frequently, unless items are constantly being added.  If you wanted to prioritise speed over memory usage, you could set the method to triple the size of the array, rather than double. 
				</p>
				<p>
					I also created getter and setter methods for List objects to access/modify the value stored in each object. <br>
					The <span class="inlinecode"><code><span class="def">add</span></code></span> method shown above will add new items onto the end of the list,
                    but it is also possible to make it rather add items to the beginning, by copying the list forward one place.
                    To see all of the code behind this project, visit the
                    <a target="_blank" href="https://github.com/mrbrianevans/java-lists" class="darker">GitHub repo</a>.
				</p>
				<p class="smallhead">
					Conclusion
				</p>
				<p>
					This method of storing data is innefficient because it requires copying the entire data set after a number of items are added, but it is more flexible than a regular array because items can be added after instantiation. 
				</p>
				<p>
					These principals are from Ronaldo Menzes' lectures :)
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
