<link rel="preconnect" href="https://fonts.gstatic.com"/>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap" rel="stylesheet">
<link href="prediction-results.css" rel="stylesheet"/>
<?php

if(isset($contests)){
    foreach ($contests as $contest){
        echo "<div class='prediction-result-block'>";
        echo "<h3 class='prediction-result-title'>".$contest["contest_name"]."</h3>";
        foreach ($contest["predictions"] as $prediction){
            echo "<li>";
            echo $prediction["username"];
            echo " guessed <span class='semibold-lato'>";
            if(strpos($contest["type"], "_date")){
                $prediction[$contest["type"]] = date("j M Y", strtotime($prediction[$contest["type"]]));
            }
            echo $prediction[$contest["type"]];
            echo "</span></li>";
        }
        $finalResult = $contest[str_replace("prediction", "outcome", $contest["type"])];
        if(strpos($contest["type"], "_date")){
            $timestamp = strtotime($finalResult);
            $finalResult = date("j M Y", $timestamp);
        }
        echo "<p>The final result was <b>$finalResult</b></p>";
        echo "</div>";
    }
}