<?php
if (isset($_GET["uploads"])) {
    require "investingAdminModel.php";
    $investAdminModel = new investingAdminModel();
    $data = $investAdminModel->getDailyUploads();
    echo json_encode($data);
}