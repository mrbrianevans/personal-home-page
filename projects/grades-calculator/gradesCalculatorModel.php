<?php


class gradesCalculatorModel
{
    private $database;
    public function __construct()
    {
        require "../../server_details.php";
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }

    public function __destruct()
    {
        $this->database->close();
    }
    /**Creates an entry in courses database table*/
    public function createCourse($courseName, $username, $courseType)
    {
        $createCoursePreparedStatement = $this->database->prepare("INSERT INTO courses (course_name, course_type, username) VALUES (?, ?, ?)");
        $createCoursePreparedStatement->bind_param("sss", $courseName, $courseType, $username);
        $createCoursePreparedStatement->execute();
        $createCoursePreparedStatement->close();

        return $this->database->insert_id;
    }
    /**Returns a numeric array of courseId's associated with a username*/
    public function getCourses($username)
    {
        $sql = "SELECT course_id FROM courses WHERE username='$username'";
        return array_values($this->database->query($sql)->fetch_all(MYSQLI_ASSOC)); //TODO: This needs testing
    }
    /**Returns an associative array of properties of the course specified*/
    public function getCourse($courseId)
    {
        $sql = "SELECT * FROM courses WHERE course_id='$courseId' LIMIT 1";
        $courseDetails = $this->database->query($sql)->fetch_assoc();
        return array(
            "name" => $courseDetails["course_name"],
            "type" => $courseDetails["course_type"],
            "datetime" => $courseDetails["datetime"]
        );
    }
    public function deleteCourse($courseId){
        $sql = "SELECT subject_id FROM subjects WHERE course_id='$courseId'";
        $subjects = $this->database->query($sql)->fetch_all();
        foreach($subjects as $subject){
            $subjectId = $subject[0];
            $sql = "DELETE FROM assessments WHERE subject_id='$subjectId'";
            $this->database->query($sql);
        }

        $sql = "DELETE FROM subjects WHERE course_id='$courseId'";
        $this->database->query($sql);

        $sql = "DELETE FROM courses WHERE course_id='$courseId'";
        $this->database->query($sql);
    }
    public function renameCourse($courseId, $newCourseName){
        $sql = "UPDATE courses SET course_name='$newCourseName' WHERE course_id='$courseId'";
        $this->database->query($sql);
    }
    public function getCourseAverage($courseId)
    {
        //TODO: Loop through all subjects, getting the averages and averaging them for the course (equally weighted)
    }


    public function addSubject($subjectName, $courseId)
    {
        $addSubjectQuery = $this->database->prepare("INSERT INTO subjects (subject_name, course_id) VALUES (?, ?)");
        $addSubjectQuery->bind_param("si", $subjectName, $courseId);
        $addSubjectQuery->execute();
        $addSubjectQuery->close();

        return $this->database->insert_id;
    }
    public function removeSubject($subjectId){
        $sql = "DELETE FROM assessments WHERE subject_id='$subjectId'";
        $this->database->query($sql);
        $sql = "DELETE FROM subjects WHERE subject_id='$subjectId'";
        $this->database->query($sql);
    }
    public function editSubject($subjectId, $newSubjectName)
    {
        $editSubjectStatement = $this->database->prepare("UPDATE subjects SET subject_name=? WHERE subject_id=?");
        $editSubjectStatement->bind_param("si", $newSubjectName, $subjectId);
        $editSubjectStatement->execute();
        $editSubjectStatement->close();
    }
    private function setSubjectAverage($subjectId, $subjectAverage){
        $editSubjectStatement = $this->database->prepare("UPDATE subjects SET average=? WHERE subject_id=?");
        $editSubjectStatement->bind_param("ii", $subjectAverage, $subjectId);
        $editSubjectStatement->execute();
        $editSubjectStatement->close();
    }
    public function getSubjects($courseId){
        $sql = "SELECT subject_id FROM subjects WHERE course_id='$courseId'";
        $subjectIds = array_values($this->database->query($sql)->fetch_all(MYSQLI_ASSOC));
        $returnResult = [];
        foreach($subjectIds as $subjectIdArray){
            $subjectId = $subjectIdArray["subject_id"];
            $returnResult[] = $subjectId;
        }
        return $returnResult;
    }
    public function getSubject($subjectId)
    {
        $sql = "SELECT subject_name, average FROM subjects WHERE subject_id=$subjectId LIMIT 1";
        $subjectDetails = $this->database->query($sql)->fetch_assoc();
        return array(
            "name"=>$subjectDetails["subject_name"],
            "average"=>$subjectDetails["average"]
        );
    }


    public function addAssessment($subjectId, $assessmentName, $maxMark, $subjectContribution)
    {
        $addAssessmentQuery = $this->database->prepare("INSERT INTO assessments (assessment_name, subject_id, max_mark, weighting) VALUES (?, ?, ?, ?)");
        $addAssessmentQuery->bind_param("siii", $assessmentName, $subjectId, $maxMark, $subjectContribution);
        $addAssessmentQuery->execute();
        $addAssessmentQuery->close();

        return $this->database->insert_id;
    }
    public function setAssessmentMark($assessmentId, $mark)
    {
        $setAssessmentStatement = $this->database->prepare("UPDATE assessments SET attained_mark=? WHERE assessment_id=?");
        $setAssessmentStatement->bind_param("ii", $mark, $assessmentId);
        $setAssessmentStatement->execute();
        $setAssessmentStatement->close();
        //TODO: Update average in Subject using newly added $mark
        $subjectId = $this->getAssessment($assessmentId)["subjectId"];
        return $this->calculateSubjectAverage($subjectId);
    }
    private function calculateSubjectAverage($subjectId){
        $assessments = $this->getAssessments($subjectId);
        $subjectAverage = 0;
        foreach($assessments as $assessment){
            $details = $this->getAssessment($assessment);
            if(isset($details["attainedMark"])) {
                $subjectAverage += ($details["weighting"] / 100) * $details["attainedMark"] * (1 / $details["maxMark"]) * 100;
            }
        }
        $this->setSubjectAverage($subjectId, $subjectAverage);
        return $subjectAverage;
    }
    public function editAssessment($assessmentId, $assessmentName, $maxMark, $subjectContribution){
        $editAssessmentStatement = $this->database->prepare("UPDATE assessments SET assessment_name=?, max_mark=?, weighting=? WHERE assessment_id=?");
        $editAssessmentStatement->bind_param("siii", $assessmentName, $maxMark, $subjectContribution, $assessmentId);
        $editAssessmentStatement->execute();
        $editAssessmentStatement->close();
    }
    public function getAssessments($subjectId){
        $sql = "SELECT assessment_id FROM assessments WHERE subject_id='$subjectId'";
        $assessmentIds = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        $returnResult = [];
        foreach($assessmentIds as $assessmentIdArray){
            $assessmentId = $assessmentIdArray["assessment_id"];
            $returnResult[] = $assessmentId;
        }
        return $returnResult;
    }
    public function getAssessment($assessmentId){
        $sql = "SELECT subject_id, assessment_name, max_mark, attained_mark, weighting FROM assessments WHERE assessment_id='$assessmentId'";
        $assessmentDetails = $this->database->query($sql)->fetch_assoc();
        return array(
            "name"=>$assessmentDetails["assessment_name"],
            "maxMark"=>$assessmentDetails["max_mark"],
            "attainedMark"=>$assessmentDetails["attained_mark"],
            "weighting"=>$assessmentDetails["weighting"],
            "subjectId"=>$assessmentDetails["subject_id"]
        );
    }
    public function deleteAssessment($assessmentId){
        $sql = "DELETE FROM assessments WHERE assessment_id='$assessmentId'";
        $this->query($sql);
    }
    private function query($sql){ //internal querying function to notify me of any errors. Returns false on failure
        $result = $this->database->query($sql);
        if(($this->database->error)){
            mail("brian@brianevans.tech", "Grades calculator error", "There has been an error with the SQL in Grades Calculator Project\n
            The SQL queried was \n$sql \nThe error message was \n".$this->database->error."\nPlease could you have a look into it, and see what could be done");
            return false;
        }else{
            return $result;
        }
    }
}