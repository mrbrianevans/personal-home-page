<?php
if(isset($_SESSION["user"])){

    require "gradesCalculatorModel.php";
    //TODO: Get all the courses, subjects and assessments into an associative array for view.php to handle
    $model = new gradesCalculatorModel();
    $courseIds = $model->getCourses($_SESSION["user"]);
    foreach($courseIds as $courseIdArray){
        $courseId = $courseIdArray["course_id"];
        $courses[$courseId] = $model->getCourse($courseId);
        $subjectIds = $model->getSubjects($courseId);
        foreach($subjectIds as $subjectIdArray){
            $subjectId = $subjectIdArray;
            $courses[$courseId]["subjects"][$subjectId] = $model->getSubject($subjectId);
            $assessmentIds = $model->getAssessments($subjectId);
            foreach($assessmentIds as $assessmentId){
                $courses[$courseId]["subjects"][$subjectId]["assessments"][$assessmentId] = $model->getAssessment($assessmentId);
            }
        }
    }
    require "view.php";
}
else{
    echo "You need to be logged in to create a course, so you can view your grades on a different device in the future";
    echo "<br>";
    echo "We are working on a feature which remembers your browser and removes the need for accounts";
}