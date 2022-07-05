<?php
//header("Access-Control-Allow-Origin: *");
require_once "flashcardStorageModel.php";
$model = new flashcardStorageModel();
$response = [];
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if(isset($_GET["packId"])){
            $packId = $_GET["packId"];
            if($flashcardPack = $model->readFlashcardPack($packId))
                $response = $flashcardPack;
            $response["status"] = true;
            if(!$flashcardPack) $response["status"] = false;
        }else{
            $response = $model->getListOfPacks();
        }
//        sleep(1);
        break;
    case "PUT":
        if($newFlashCards = json_decode(file_get_contents("php://input"), true)){
            $id = $newFlashCards["metadata"]["id"];
            $response["status"] =  $model->modifyFlashcardPack($id, $newFlashCards);
        }
        else{
            $response["status"] = false;
            $response["error"] =  "Invalid JSON format used";
        }
        break;
    case "POST":
        if($newFlashCards = json_decode(file_get_contents("php://input"), true)){
            $newId = $model->writeNewFlashcardpack($newFlashCards);
            $response["status"] = true;
            $response["id"] =  $newId;
        }
        else{
            $response["status"] = false;
            $response["error"] =  "Invalid JSON format used";
        }
        break;
    case "DELETE":
        $packId = $_GET["packId"];
        $response["status"] =  $model->deleteFlashcardPack($packId);
        $response["message"] =  $response["status"] ? "Successfully deleted" : "Failed to delete";
        break;
    default:
        $response["status"] = false;
        $response["message"] =  "Unauthorised method used";
        break;
}

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: content-type");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
echo json_encode($response);