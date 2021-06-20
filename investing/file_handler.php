<?php
if (isset($_GET["graphDataFile"])) {
    $fileLocation = rawurldecode($_GET["graphDataFile"]) . ".json";
    echo json_encode(json_decode(file_get_contents($fileLocation)));
}
elseif (isset($_GET["colour"])) {
    $keyRequest = strtoupper($_GET["colour"]);
    $colourMapper = json_decode(file_get_contents("colours.json"));
    if(isset($colourMapper->$keyRequest))
        echo $colourMapper->$keyRequest;
}