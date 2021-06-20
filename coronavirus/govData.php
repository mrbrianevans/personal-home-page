<?php
$table_heading = "<table>";
$govRequest = curl_init("https://www.gov.uk/guidance/coronavirus-covid-19-information-for-the-public");
curl_setopt($govRequest, CURLOPT_RETURNTRANSFER, 1);
$govweb = curl_exec($govRequest);
$start_pos = strpos($govweb, $table_heading);
$length = strpos($govweb, "</table>")-$start_pos+8;
$table_data = substr($govweb, $start_pos, $length);
$table_data = str_replace("<table>", "<table class='coronavirus'>", $table_data);
$table_data = str_replace(' style="text-align: right"', "", $table_data);
$table_data = str_replace(' scope="col"', "", $table_data);
echo $table_data;