<form action="exam.php" method="get">
    First name: <input type="text" name="name"><br>
    Email: <input type="text" name="email"><br>
    <input type="submit">
</form>
<ul id="list">
    <li>first</li>
    <li>second</li>
    <li>third</li>
</ul>
<input type="button" value="Upper case" onclick="upperFunction()"/>
<script>
    function upperFunction(){
        document.getElementById("list").childNodes[1].nodeValue = "SECOND";
    }
</script>


<?php
if (isset($_GET)) {
    $correct = true;
    if(!preg_match("'(^[A-Za-z\s]+$)'", $_GET["name"])){
        $correct = false;
        echo "Name is invalid<br>";
    }
    if(($_GET["email"])<>""){
        if (!filter_input(INPUT_GET, "email", FILTER_VALIDATE_EMAIL)) {
            $correct = false;
            echo "Email is invalid<br>";
        }
    }
    if($correct) header("Location: ./success.php");
}