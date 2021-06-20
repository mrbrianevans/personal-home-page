<?php
if(isset($matches)){
    echo "<div class='flex-container'/>";
    foreach($matches as $match){
        require "personView.php";
    }
    echo "</div>";
}else{
    echo "<p>Sorry, no matches found in list</p>";
}