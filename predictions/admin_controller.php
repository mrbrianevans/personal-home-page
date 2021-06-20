<?php
if ($_SESSION['user'] == 'brianevans') {
    require "admin_view.php";
} else if($_SESSION['user'] == ''){
    echo "You must be logged in to post predictions <a class='button' href='/login/'>Login</a>";
}else{
    echo "<div id='showRequestFormForm'>";
    echo "<button class='green' onclick='showRequestForm()'>Request a new contest</button>";
    echo "</div>";
}