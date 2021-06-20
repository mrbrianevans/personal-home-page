<?php
require_once "templateModel.php";
$model = new flashcardStorageModel();

switch ($_GET[""]){
    case "1": // call a function from the model on the get request variable, and require a view to display the output
        break;
    case "2":// call a function from the model on the get request variable, and require a view to display the output
        break;
    default:
        echo "Request not valid";
        break;
}