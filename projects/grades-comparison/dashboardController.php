<?php


require "jcqGradeStatisticsModel.php"; // this causes an error
$jcqModel = new jcqGradeStatisticsModel();
if(isset($_GET["readPDF"])) require "pdfReader.php";
elseif (isset($_GET["subject"])) { // display a dashboard for a particular subject
    $subjectParticipationByGender = $jcqModel->getIndividualSubjectGenderParticipation($_GET["subject"]);
    $subjectAttainmentByGender = $jcqModel->getNoncumulativeSubjectGradePercentageByGender($_GET["subject"]);
    ?>
    <div id="subjectDashboard"></div>
    <script type="text/javascript">
        let genderData = JSON.parse('<?=$subjectParticipationByGender?>');
        let gradeData = JSON.parse('<?=$subjectAttainmentByGender;?>');
    </script>
    <script type="text/javascript" src="/googleChartsLocal.js"></script>
    <script src="subjectGrapher.js"></script>

    <?php
}else { // display the dashboard of all subjects
    if(!isset($_SESSION['firstTimeOnGradesExplorer'])){
        echo "<div class='infoMessage'><span class='infoIcon'>i</span><span>To see the details of a specific subject, click on one of the bars, and then click <button class='bigButton' disabled>View</button></span></div>";
        $_SESSION["firstTimeOnGradesExplorer"] = false;
    }
    $subjectParticipationByGender = $jcqModel->getSubjectParticipationByGenderStackedColumnData();
    $totalParticipationByGender = $jcqModel->getTotalParticipationByGender();
    $totalParticipationByCountry = $jcqModel->getTotalParticipationByCountry();
    $subjectParticipationByGrades = $jcqModel->getSubjectParticipationByGradeColumnChart();
    ?>

    <div id="genderParticipationGraph"></div>
    <button id="switchToGrades" disabled>Switch to grades?</button>
    <p class="smallhead">By Grade:</p>
    <div class="flexGraphsContainer"><div id="gradesColumnChartContainer"></div><div id="gradesPieChartContainer"></div></div>
    <h2>Graphs in development</h2>
    <p class="smallhead">By Country:</p>
    <div class="flexGraphsContainer"><div id="geoChartContainer"></div><div id="geoTableContainer"></div></div>
    <script type="text/javascript">
        let data = JSON.parse('<?=$subjectParticipationByGender?>');
        let totalGenderParticipationData = JSON.parse('<?=$totalParticipationByGender;?>');
        let totalCountryParticipationData = JSON.parse('<?=$totalParticipationByCountry?>');
        let totalGradeParticipationData = JSON.parse('<?=$subjectParticipationByGrades?>');
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="homepageGrapher.js"></script>
    <?php

}