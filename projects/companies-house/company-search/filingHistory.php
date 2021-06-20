<?php
$companyNumber = $_GET["filing_history"];

$companyRequest = curl_init("https://api.companieshouse.gov.uk/company/$companyNumber/filing-history");
curl_setopt($companyRequest, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($companyRequest, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
curl_setopt($companyRequest, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($companyRequest);
curl_close($companyRequest);
$result = json_decode($result, true);
//print_r($result);
echo "<ol>";
foreach($result["items"] as $filing){
    $category = ucwords($filing["category"]);
    $date = date("j M Y", strtotime($filing["action_date"]));
    $link = "?company_number=$companyNumber&transaction_id=" . $filing["transaction_id"];
    echo "<li>";
    echo "<a class='darker' href='$link'>";
    echo "$category filed on $date";
    echo "</a>";
    echo "</li>";
}
echo "</ol>";