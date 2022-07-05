<?php

$url = "https://api.beta.ons.gov.uk/v1";

if(isset($_GET["list"])){
    switch ($_GET["list"]){
        case "datasets":
            $query = "/datasets";
            $request = curl_init($url.$query);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($request);
            $response = json_decode($response, true);

            foreach($response["items"] as $dataset){
                echo "<div>";
                echo "<b>";
                echo $dataset["title"];
                echo "</b><div>";
                echo $dataset["description"];
                echo "</div>";
                $editionsQuery = "/datasets/" . $dataset["id"] . "/editions";
                $editionsRequest = curl_init($url . $editionsQuery);
                curl_setopt($editionsRequest, CURLOPT_RETURNTRANSFER, 1);
                $editionsResponse = curl_exec($editionsRequest);
                $editionsResponse = json_decode($editionsResponse, true);
                foreach($editionsResponse["items"] as $edition){
                    $editionName = preg_match("/[a-z]+-[a-z]+/i", $edition["edition"]) ? ucwords(str_replace("-", " ", $edition["edition"])) : $edition["edition"];
                    echo "<a href='?dataset=" . $dataset["id"] . "&edition=" . $edition["edition"] . "'><button>View $editionName</button></a>";
                }
                echo "</div>";
            }
            break;
        case "codeLists":
            $query = "/code-lists";
            $request = curl_init($url.$query);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($request);
            $response = json_decode($response, true);
            echo "<ul>";
            foreach($response["items"] as $codeList){
                $codeName = $codeList["links"]["self"]["id"];
                echo "<li><a class='darker' href='?code=$codeName'>$codeName</a></li>";
            }
            echo "</ul>";
            break;
    }
}elseif(isset($_GET["dataset"])){
    if(isset($_GET["observation"])){
//        echo "Received Query looking like this: " . $_SERVER["QUERY_STRING"];
        $observationRequestURL = $url . "/datasets/" . $_GET["dataset"] . "/editions/" . $_GET["editions"] . "/versions/" . $_GET["versions"] . "/observations?";
        $presetQueries = array("", "dataset", "editions", "versions", "observation", "geoLabel");
        echo "<p class='smallhead'>".ucwords(str_replace("-", " ", $_GET["dataset"]))."</p>";
        echo "<div class='filters-flex'>";
        foreach($_GET as $name=>$value) {
            if(!array_search($name, $presetQueries)) $observationRequestURL .= "$name=$value&";
            if(!array_search($name, $presetQueries)) echo "<div>" . ucwords($name) . ": " . ucwords($value) . "</div>";
        }
        echo "</div>";
        $observationRequestURL = substr($observationRequestURL, 0, strlen($observationRequestURL)-1);
        echo "<br>Outgoing Query looking like this: $observationRequestURL<br>";
        $observationRequest = curl_init($observationRequestURL);
        curl_setopt($observationRequest, 19913, 1);
        $observationResponse = json_decode(curl_exec($observationRequest), true);
        echo "<ul>";
        foreach($observationResponse["observations"] as $observation){
            $unitFirst = true;
            if($observationResponse["unit_of_measure"]=="Hours per week" || $observationResponse["unit_of_measure"]=="%")
                $unitFirst = false;
            $decimalPlaces = strlen($observation["observation"]) < 3 ? 2 : 0;
            $observationValue = is_numeric($observation["observation"]) ? number_format($observation["observation"], $decimalPlaces) : $observation["observation"];
            echo "<li>";
            echo $unitFirst ? $observationResponse["unit_of_measure"] . $observationValue : $observationValue . " " . $observationResponse["unit_of_measure"];
            echo "</li>";
        }
        echo "</ul>";
    }
    else{
        $query = "/datasets/" . $_GET["dataset"] . "/editions";
        $request = curl_init($url . $query);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($request);
        if(strlen($response)<5) echo "Dataset not found :(";
        $response = json_decode($response, true);
        foreach ($response["items"] as $info) {
            if ($info["edition"] == $_GET["edition"]) {
                echo "<p><b>" . $info["edition"] . "</b></p>";
                $timeSeriesURL = $info["links"]["latest_version"]["href"] . "/dimensions";
                echo $timeSeriesURL;
                $latestVersionId = $info["links"]["latest_version"]["id"];
                echo "Data Dimensions:";
                $timeSeriesRequest = curl_init($timeSeriesURL);
                curl_setopt($timeSeriesRequest, CURLOPT_RETURNTRANSFER, 1);
                $timeSeriesResponse = json_decode(curl_exec($timeSeriesRequest), true);
                echo "<form method='get' action=''><ol>";
                echo "<input type='hidden' name='dataset' value='" . $_GET["dataset"] . "' hidden/>";
                echo "<input type='hidden' name='editions' value='".$info["edition"]."'/>";
                echo "<input type='hidden' name='versions' value='$latestVersionId' hidden/>";
                foreach ($timeSeriesResponse["items"] as $dimension) {
                    $dimensionOptionsURL = $dimension["links"]["options"]["href"];
//                    echo $dimensionOptionsURL;
                    $dimensionOptionsRequest = curl_init($dimensionOptionsURL);
                    curl_setopt($dimensionOptionsRequest, 19913, 1);
                    $dimensionOptionsResponse = json_decode(curl_exec($dimensionOptionsRequest), true);
                    echo "<li>";
                    $dimensionName = $dimension["name"];
                    echo ucwords($dimensionName);
                    if(true){
                        echo "<datalist id='datalist$dimensionName'>";
                        foreach ($dimensionOptionsResponse["items"] as $dimensionOption) {
                            $dimensionId = $dimensionOption["links"]["code"]["id"];
                            $dimensionLabel = $dimensionOption["label"];
                            $dimensionId = $dimensionId=="Year"? $dimensionLabel:$dimensionId;
                            echo "<option value='$dimensionId'>$dimensionLabel</option>";
                        }
                        echo "</datalist>";
                        echo "<input list='datalist$dimensionName' type='text' name='$dimensionName' placeholder='$dimensionLabel'/>";
                    }else{
                        echo "<ol>";
                        foreach ($dimensionOptionsResponse["items"] as $dimensionOption) {
                            $dimensionId = $dimensionOption["links"]["code"]["id"];
                            $dimensionLabel = $dimensionOption["label"];
                            $dimensionId = $dimensionId=="Year"? $dimensionLabel:$dimensionId;
                            echo "<li><input type='radio' name='$dimensionName' placeholder='$dimensionId'>$dimensionLabel</li>";
                        }
                        echo "</ol></li>";
                    }

                }
                echo "</ol>";
                echo "<button name='observation' value='true' type='submit'>Retrieve</button></form>";
                break;
            }
        }
    }
} elseif (isset($_GET["code"])) {
    $query = "/code-lists";
    $request = curl_init($url.$query);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($request);
    $response = json_decode($response, true);
    echo "<ul>";
    foreach($response["items"] as $codeList){
        $codeName = $codeList["links"]["self"]["id"];
        echo "<li>$codeName</li>";
    }
    echo "</ul>";
}
else{
    require "noquery.html";
}



