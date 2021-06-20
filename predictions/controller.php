<?php
require "predictionsModel.php";
$pModel = new predictionsModel();
$contests = $pModel->getAllContests();
$entry_buttons = [];
foreach($contests as $contest){
    $entry_buttons[$contest['contest_name']] = $pModel->checkForUsernameInContest($uname, $contest['contest_name']);
}

$contest_details = [];
foreach ($contests as $contest) {
    foreach ($pModel->getEntriesOfContest($contest['contest_name']) as $row=>$data) {
        $contest_details[$contest['contest_name']][] = $data;
    }
    unset($data);
}
unset($label);
unset($contest);


unset($contest);
require "admin_controller.php";
require "view.php";