<?php

$style = "table.cbt th{    width: 20%;}table.cbt td{    vertical-align: top;}table.cbt{    border-collapse: collapse;    width: 100%}table.cbt tr, td, th{    border-width: 1px;    border: solid #2F302F;    padding: 3px;}div.mainbody{    background-color: rgba(245, 245, 245, 1);    width: 80%;    max-width: 1000px;    border: rgba(0, 0, 0, 0.8);    box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);    margin: 20px;    padding: 10px    }body.background{    background-color: rgba(0, 0, 0, 0.7);    }";

//$_POST = json_decode(file_get_contents("php://input"), true);
// this is required for the JSON request made by the app, disabled for testing through the browser

if(isset($_POST["action"])){
    $emailAddress = $_POST["address"];
    $username = ucwords(strtolower($_POST["username"]));
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
        if(strlen($_POST["part$partNumber"])!=0){
            $columnData[$columnHeaders[$partNumber-1]] = htmlspecialchars($_POST["part$partNumber"]);
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
        $message .= "<p>This email was automatically generated at $time on the $day of $monthYear</p>";
        $message .= "</body></html>";
        switch ($_POST["action"]) {
            case "preview":
                echo $message;
                break;
            case "email":
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: <CBTApp>" . "\r\n";
                if(mail("$emailAddress", "CBT Update", "$message", "$headers"))
                    echo "Successfully emailed answers to $emailAddress";
                else
                    echo "Failed to email answers to $emailAddress";
                break;
            default:
                echo "action=" . $_POST["action"] . " is not a valid request. Please refer to the docs for valid actions";
        }
                echo "<br><a href='.'><button>Back</button></a>";
    }
}
else{
    ?>
    <h3>API Tester</h3>
    <form method="post" action="controller.php">
        <label>Enter email address for testing <input type="email" name="address" placeholder="therapy@gmail.com"/></label><br/>
        <input type="hidden" name="username" value="Zee"/>
        <input type="hidden" name="part1" value="Whenever I think about talking to or messaging a friend, I think they just won’t want to talk to me"/>
        <input type="hidden" name="part2" value="Send a message to D tonight saying ‘Hey, how’s things?’"/>
        <input type="hidden" name="part3" value="She will send back a message telling me not to message her again."/>
        <input type="hidden" name="part4" value="She messages me back in a nice way, or even ends up sending a few messages"/>
        <input type="hidden" name="part5" value="After an hour she messaged me back. It was a nice message. My thoughts were not right. We have messaged since and are going to meet up. I don’t need to just believe the upsetting thoughts I have. I can test them."/>
        <button type="submit" value="preview" name="action">View Sample</button>
        <button type="submit" value="email" name="action">Email Sample</button>
    </form>
<hr>
    Enter custom values:
    <form method="post" action="controller.php">
        <label>Therapists email address: <input name="address" placeholder="therapy@gmail.com"></label>
        <br>
        <label>Patients name: <input name="username" placeholder="Zee"></label>
        <br>
        <label>What thoughts when? <textarea name="part1"></textarea></label>
        <br>
        <label>Experiment plan: <textarea name="part2"></textarea></label>
        <br>
        <label>If the thoughts are right then… <textarea name="part3"></textarea></label>
        <br>
        <label>If the thoughts are not right then… <textarea name="part4"></textarea></label>
        <br>
        <label>What did I learn? <textarea name="part5"></textarea></label>
        <br>
        <label>Preview email <input type="radio" name="action" value="preview"></label>
        <br>
        <label>Send email<input type="radio" name="action" value="email"></label>
        <br>
        <button type="submit">Request API</button>
    </form>

        <h3>JSON format</h3>
        <p>
            If sending JSON, this is the interface that the array must adhere to:

            {"id":"053177a1-200b-4771-b558-91a1335d2dd9","name":"First experiment","field1":"What thoughts happen when??","field2":"","field3":"","field4":"","field5":"", "address": "gmail.com", "username":"Zee"}
        </p>
    <?php

}
