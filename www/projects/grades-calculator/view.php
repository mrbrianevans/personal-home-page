<div id="newCourseForm" class="courseBox">

</div>
<button onclick="newCourse()" id="newCourseOpenFormButton">New course</button>
<?php
if(isset($courses)){
foreach($courses as $courseId=>$course){
    //TODO: Display course details such as course name, and date created
    require("singleCourseView.php");
}
}
?>

