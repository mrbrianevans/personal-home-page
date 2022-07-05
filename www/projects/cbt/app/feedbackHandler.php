<?php
echo "request received\n";
if(isset($_POST["feedback"])){
    if(mail("brian@brianevans.tech", "CBT App Feedback", "Feedback: \n\n".$_POST["feedback"]."\n\nKind regards"))
        echo "mail sent\n";
    else
        echo "mail not sent\n";
}