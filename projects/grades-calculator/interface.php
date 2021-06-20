<?php
//TODO: Add setters for all values/methods in gradesCalculatorModel
if(str_replace("www.", "", parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST))==="brianevans.tech"){
    require "gradesCalculatorModel.php";
    $model = new gradesCalculatorModel();
}else{
    echo "Sorry, not authorised";
}
if(isset($_GET["courseName"])){
    echo $model->createCourse($_GET["courseName"], $_GET["username"], $_GET["courseType"]);
}elseif(isset($_GET["subjectName"])){
    echo $model->addSubject($_GET["subjectName"], $_GET["courseId"]);
}elseif(isset($_GET["assessmentName"])){
    $subjectContribution = str_replace("%", "", $_GET["subjectContribution"]);
    echo $model->addAssessment($_GET["subjectId"], $_GET["assessmentName"], $_GET["maxMark"], $subjectContribution);
}
elseif (isset($_GET["courseId"])) {
    //Deal with courses
    if(isset($_GET["fetch"])){
        switch ($_GET["fetch"]){
            case "name":
                echo $model->getCourse($_GET["courseId"])["name"];
                break;
            case "type":
                echo $model->getCourse($_GET["courseId"])["type"];
                break;
            case "display":
                
            default:
                echo "Attribute not valid";
                break;
        }
    }
    elseif(isset($_GET["set"])){
        switch ($_GET["set"]){
            case "name":
                $model->renameCourse($_GET["courseId"], $_GET["value"]);
        }
    }
    elseif(isset($_GET["delete"])){
        $model->deleteCourse($_GET["courseId"]);
    }
} elseif (isset($_GET["subjectId"])) {
    //Deal with subjects
    if(isset($_GET["fetch"])){
        switch ($_GET["fetch"]){
            case "name":
                echo $model->getSubject($_GET["subjectId"])["name"];
                break;
            case "average":
                echo $model->getSubject($_GET["subjectId"])["average"];
                break;
            default:
                echo "Attribute not valid";
                break;
        }
    }
    elseif(isset($_GET["delete"])){
        $model->removeSubject($_GET["subjectId"]);
    }
} elseif (isset($_GET["assessmentId"])) {
    //Deal with assessments
    if(isset($_GET["fetch"])){
        switch ($_GET["fetch"]){
            case "name":
                echo $model->getAssessment($_GET["assessmentId"])["name"];
                break;
            case "attainedMark":
                echo $model->getAssessment($_GET["assessmentId"])["attainedMark"];
                break;
            default:
                echo "Attribute not valid";
                break;
        }
    }elseif(isset($_GET["mark"])){
        echo number_format($model->setAssessmentMark($_GET["assessmentId"], $_GET["mark"]), 1);
    }elseif(isset($_GET["delete"])){
        $model->deleteAssessment($_GET["assessmentId"]);
    }
} elseif(isset($_GET["icon"])){
    switch ($_GET["icon"]){
        case "delete":
            echo file_get_contents("icons/thickCross.svg");
    }
}