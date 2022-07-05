<?php
echo "<div class='courseBox' id='courseBox$courseId'>";
echo "<h3 class='courseName'>" . $course["name"] . " " . $course["type"] . "</h3>";
foreach($course["subjects"] as $subjectId=>$subject){
    //TODO: Display subject details such as name and average
    echo "<div class='subjectBox'>";
    $subjectAveragePercentage = $subject["average"] . "%";
//    echo "Subject: " . $subject["name"] . " with an average of $subjectAveragePercentage";
    echo "<div class='subjectAverageTotal'><span class='subjectName'>" . $subject["name"] .
        "</span><div class='subjectAverageAchieved' style='width: $subjectAveragePercentage'>$subjectAveragePercentage</div></div>";
    foreach($subject["assessments"] as $assessmentId=>$assessment){
        //TODO: Display assessment details
        echo "<div class='assessmentBox'>";
        echo "Assessment: ";
        $assessmentPercentage = number_format($assessment["attainedMark"]/$assessment["maxMark"]*100, 1);
        $assessmentMarkSummary = $assessment["attainedMark"]." out of " . $assessment["maxMark"];
        echo $assessment["name"] . " $assessmentMarkSummary = $assessmentPercentage%";
        echo "</div>";
    }
    echo "</div>";
}
echo "<button onclick='editCourse(\"$courseId\")'>Edit</button>";
echo "<button onclick='removeCourse(\"$courseId\")'>Delete</button>";
echo "</div>";