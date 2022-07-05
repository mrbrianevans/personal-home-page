<?php
require "genealogyEditingModel.php";
require "genealogyRetrieveModel.php";
$editingModel = new genealogyEditingModel();
$getModel = new genealogyRetrieveModel();
if(count($_GET)){
    switch ($_GET["action"]) {
        case "update":
            $id = $_GET["id"];
            $name = $_GET["name"];
            $place = $_GET["place"];
            $father = $_GET["father"];
            $mother = $_GET["mother"];
            $siblings = explode("\r\n", $_GET["siblings"]);
            $mentions = explode("\r\n", $_GET["mentions"]);
            $spouses = explode("\r\n", $_GET["spouses"]);
            $gender = $_GET["gender"];
            $otherNames = explode("\r\n", $_GET["names"]);
            if(strlen($id)){
                if($newId=$editingModel->updatePersonDetails($id, $name, $place, $father, $mother, $siblings,
                                                        $mentions, $gender, $otherNames, $spouses)) {
                    echo "Successfully updated $name";
                    if($match = $getModel->getPersonById($newId)) require "personView.php";
                    else echo "<p>Failed to find $name (from $id to $newId)</p>";
                }
                else{
                    echo "<p>Failed to update $name</p>";
                    if($match = $getModel->getPersonById($id)) require "personView.php";
                    else echo "<p>Failed to find $name ($id)</p>";
                }
            }else{
                if($id=$editingModel->addPerson($name, $place, $father, $mother, $siblings, $mentions, $gender, $otherNames, $spouses)) {
                    echo "Successfully added $name";
                    if($match = $getModel->getPersonById($id)) require "personView.php";
                    else echo "<p>Failed to find $name ($id)</p>";
                }
                else{
                    echo "Failed to add $name";
                }

            }
            break;
        case "get":
            $idQuery = $_GET["id"];
            if($match = $getModel->getPersonById($idQuery)) require "personView.php";
            else echo "No match found";
            break;
        case "search-by-name":
            $nameQuery = $_GET["name"];
            if($matches = $getModel->searchByName($nameQuery)) require "personListView.php";
            if(is_array($matches) && count($matches)==1) header("Location: ?action=ancestry&id=" . array_key_first($matches));
            else echo "No match found";
            break;
        case "update-form":
            $id = $_GET["id"];
            if($match = $getModel->getPersonById($id)) require "personUpdateView.php";
            else echo "No match found";
            break;
        case "ancestry":
            $id = $_GET["id"];
            $familyTree = [];
            if($ancestors = $getModel->getAncestors($id)) $familyTree["ancestors"] = $ancestors;
            else echo "<p>No ancestry</p>";
            if($children = $getModel->getImmediateChildren($ancestors[array_key_last($ancestors)]["id"])) $familyTree["children"] = $children;
            else echo "<p>No children</p>";
            if($siblings = $getModel->getSiblings($id)) $familyTree["siblings"] = $siblings;
            else echo "<p>No siblings</p>";
            require "personAncestryView.php";
            $id = $_GET["id"];
            echo "<script type='text/javascript'>window.location.hash = '$id' </script>";
            $modal = array("style"=>"person-details-modal", "content"=>"This is the persons details");
            break;
        case "delete":
            $id = $_GET["id"];
            if($editingModel->deletePerson($id)){
                echo "Successfully deleted person";
            }else echo "Failed to delete";
            break;
        case "bulk-add":
            $sons = explode("\r\n", $_GET["sons"]);
            $fatherId = $_GET["id"];
            $motherId = $_GET["mother"];
            $bookReference = $_GET["mention"];
            $addedSons = $editingModel->addMultipleSons($fatherId, $sons, $bookReference, $motherId);
            require "addedListView.php";
            break;
        default:
            echo "Action <b>".$_GET["action"]."</b> not defined";
            break;
    }
}else {
    if($fathers = $getModel->getListOfPotentialFathers()){
//        print_r($fathers);
        echo "<datalist id='fathers'>";
        foreach($fathers as $fatherId=>$fatherName){
            echo "<option value='$fatherId'>$fatherName</option>";
            $allPeopleList[] = $fatherName;
        }
        echo "</datalist>";
    }
    if($mothers = $getModel->getListOfPotentialMothers()){
        echo "<datalist id='mothers'>";
        foreach($mothers as $motherId=>$motherName){
            echo "<option value='$motherId'>$motherName</option>";
            $allPeopleList[] = $motherName;
        }
        echo "</datalist>";
    }
    echo "<datalist id='all-people'>";
    foreach($getModel->getAllPeople() as $shortName=>$longName){
        echo "<option value='$shortName'>$longName</option>";
    }
    echo "</datalist>";
    require "dashboardView.php";
}