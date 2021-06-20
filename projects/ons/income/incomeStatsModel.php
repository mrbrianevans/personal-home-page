<?php


class incomeStatsModel
{
    public static function getListOfStatisticsOptions()
    {
        $url = "https://api.beta.ons.gov.uk/v1/datasets/ashe-table-7-earnings/editions/time-series/versions/1/dimensions/statistics/options";
        $optionsRequest = curl_init($url);
        curl_setopt($optionsRequest, 19913, 1);
        $optionsResponse = curl_exec($optionsRequest);
        curl_close($optionsRequest);
        $optionsArray = json_decode($optionsResponse, true);
        $allOptions = [];
        foreach($optionsArray["items"] as $item){
            $allOptions[$item["option"]] = $item["label"];
        }
        unset($allOptions["25th-percentile"], $allOptions["75th-percentile"], $allOptions["mean"]);
        $allOptions["median"] = "50th percentile";
        asort($allOptions);
        return json_encode($allOptions);
    }

    public static function getObservation($statistic)
    {

        $url = "https://api.beta.ons.gov.uk/v1/datasets/ashe-table-7-earnings/editions/time-series/versions/1/observations?".
            "time=2017&geography=E92000001&sex=all&workingpattern=full-time&earnings=annual-pay-gross&statistics=$statistic";
        $optionsRequest = curl_init($url);
        curl_setopt($optionsRequest, 19913, 1);
        $optionsResponse = curl_exec($optionsRequest);
        curl_close($optionsRequest);
        $optionsArray = json_decode($optionsResponse, true);
        preg_match("/^[1-9]/", $statistic, $number);
        usleep(($number[0]??5)*200000);
        return $optionsArray["observations"][0]["observation"];
    }
}