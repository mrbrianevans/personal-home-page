<?php
if(count($_GET)>0){ // form has been submitted
    require "fpvCalculatorModel.php";
    $specificCurrentMeasurements = array();
    foreach($_GET as $measured=>$charged){
        if(is_numeric($measured)) $specificCurrentMeasurements[$measured] = $charged;
    }
    $scale = $_GET["scale"];
    $scatterChartPoints = fpvCalculatorModel::measuredChargedCurrentScatterChart($specificCurrentMeasurements);
    $columnChartBars = fpvCalculatorModel::calculateCurrentColumnGraph($specificCurrentMeasurements, $scale);
    $newCurrentScale = fpvCalculatorModel::calculateCurrent($specificCurrentMeasurements, $scale);

    require "currentView.php";
    ?>

    <?php
}else{
    require "form.php";
}


