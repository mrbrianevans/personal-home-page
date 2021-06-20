<?php


class fpvCalculatorModel
{
    /**
     * @param $measuredToChargedArray array (assoc) of measured=>charged, eg.: [5600=>1200]
     * @param $previousScale int the value of scale in betaflight to get the measured values
     * @return int the calculated scale value to enter into betaflight
     */
    public static function calculateCurrent($measuredToChargedArray, $previousScale)
    {
        $ratiosArray = [];
        foreach($measuredToChargedArray as $measured=>$charged){
            $ratio = $measured/$charged;
            $ratiosArray[] = $ratio;
        }
        //TODO: Filter out anomalies
        $averageRatio = array_sum($ratiosArray) / count($ratiosArray);
        $newScale = $previousScale / $averageRatio;
        return (int) $newScale;
    }
    public static function measuredChargedCurrentScatterChart($measuredToChargedArray){
        $graphData = array();
        foreach($measuredToChargedArray as $measured=>$charged) $graphData[$measured] = $charged;
        return json_encode($graphData);
    }
    public static function calculateCurrentColumnGraph($measuredToChargedArray, $previousScale){
        $ratiosArray = [];
        foreach($measuredToChargedArray as $measured=>$charged){
            $ratio = $previousScale/($measured/$charged);
            $ratiosArray[] = $ratio;
        }
        $ratiosArray["Average"] = round(array_sum($ratiosArray)/count($ratiosArray), 1);
        return json_encode($ratiosArray);
    }
}