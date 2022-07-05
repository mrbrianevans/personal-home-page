<?php

$style = "table.cbt th{    width: 20%;}table.cbt td{    vertical-align: top;}table.cbt{   border-collapse: collapse;    width: 100%}table.cbt tr, td, th{    border-width: 1px;    border: solid #2F302F;    padding: 3px;}div.mainbody{    background-color: rgba(245, 245, 245, 1);    width: 80%;    max-width: 1000px;    border: rgba(0, 0, 0, 0.8);    box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);    margin: 20px;    padding: 10px    }body.background{    background-color: rgba(0, 0, 0, 0.7);    }";
$requestBody = file_get_contents("php://input");
$requestVariables = json_decode($requestBody, true);

$emailAddress = $requestVariables["address"];
$username = ucwords(strtolower(htmlspecialchars($requestVariables["username"])));
$fromAddress = strtolower($username) . "@cbt-app.co.uk";
$experimentName = ucwords(strtolower(htmlspecialchars($requestVariables["name"])));
$columnData = [];
$columnHeaders = [
    "What thoughts when?<br>Part 1",
    "Experiment plan<br>Part 2",
    "If the thoughts are right then…<br>Part 3",
    "If the thoughts are not right then…<br>Part 4",
    "What did I learn?<br>Part 5"
];

$partsIncluded = [];
foreach(range(1, 5) as $partNumber){
    if(strlen($requestVariables["field$partNumber"])!=0){
        $columnData[$columnHeaders[$partNumber-1]] = htmlspecialchars($requestVariables["field$partNumber"]);
        $partsIncluded[] = $partNumber;
    }
}
if(!count($partsIncluded)==max($partsIncluded)) echo "Parts missing, count=".count($partsIncluded).", max=".max($partsIncluded);
else{
    $message = "<html><head><style>$style</style></head>";
    $message .= "<body class='background'><div class='mainbody'><h2>CBT Experiment Update from $username</h2>";
    $message .= "<p>This is an automated message from the CBT App</p>";
    $message .= "<p>$username has submitted ";
    foreach($partsIncluded as $part) $message .= " PART$part ";
    $message .= "of the experiment titled: <b>$experimentName</b>";
    $message .= "</p>";
    if(count($partsIncluded)==5) $message .= "<p>This experiment is complete</p>";
    $message .= "<table class='cbt'><tr>";
    foreach($columnData as $columnLabel=>$columnEntry){
        $message .= "<th>";
        $message .= $columnLabel;
        $message .= "</th>";
    }
    $message .= "</tr>";
    $message .= "<tr>";
    foreach($columnData as $columnLabel=>$columnEntry){
        $message .= "<td>";
        $message .= $columnEntry;
        $message .= "</td>";
    }
    $message .= "</tr></table></div>";
//        $message .= "<p>If this email was not intended for you, please ignore it</p>";
//        $message .= "<p>If there are any problems with this email, please contact the owner of the CBT App</p>";
    $time = date("g:ia");
    $day = date("jS");
    $monthYear = date("F, Y");
    $message .= "<p>This email was generated at $time on the $day of $monthYear</p>";
    $message .= "</body></html>";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: $fromAddress" . "\r\n";
    $headers .= "Organization: CBT App" . "\r\n";
    $response = [];
    if(!mail("$emailAddress", "CBT Update", "$message", "$headers")){
        $response["message"] = "Email failed to send. Please try again";
        mail("brian@brianevans.tech", "CBT mailer failed", "An attempted email to $emailAddress failed to send. This was the request: \n$requestBody");
    }else{
        $response["message"] = "Email sent";
    }
    echo json_encode($response); // api response
}