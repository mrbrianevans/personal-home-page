<?php
$originalRequestTime = microtime(true);
$returnRequestTime = true;
if(isset($_GET["statistic"])){
    require_once "companyStatisticsModel.php";
    $model = new companyStatisticsModel();
    switch ($_GET["statistic"]){
        case "psc-age":
            $data = $model->ageOfPersons();
            $returnRequestTime = false;
            break;
        case "company-age":
            $data = $model->companyRegistrationDates();
            break;
        case "sic":
            $data = $model->sicCodes();
            break;
        case "active":
            $data = $model->active();
            break;
        default:
            $data = "Statistic not found";
            break;
    }
    echo json_encode($data);
    $requestProcessingTime = number_format(microtime(true) - $originalRequestTime, 6);
    if($returnRequestTime) echo "<div style='
text-align: right;
    font-size: smaller;
    color: #2F302F;'>Request processing took $requestProcessingTime seconds</div>";
}else{
    require_once "navigator.php";
}
