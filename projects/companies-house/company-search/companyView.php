<?php
$companyNumber = $_GET["company_info"];
$companyRequest = curl_init("https://api.companieshouse.gov.uk/company/$companyNumber");
curl_setopt($companyRequest, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($companyRequest, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
curl_setopt($companyRequest, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($companyRequest);
curl_close($companyRequest);
$result = json_decode($result, true);

echo "<p class='smallhead'><a class='darker' href='?company_info=".$_GET["company_info"]."'>".$result['company_name']."</a></p>";
echo "<b>Industry: </b>";
echo "<ul>";
$sicCodes = json_decode(file_get_contents("../sic.json"), true);
foreach($result["sic_codes"] as $sic){
    if(isset($sicCodes[$sic]))
        echo "<li>" . $sicCodes[$sic] . "</li>";
    else
        echo "<li>" . $sicCodes[substr($sic, 0, strlen($sic)-1)] . "</li>";
}
echo "</ul>";

echo "<ul>";
foreach($result as $key=>$value){
    echo "<li>";
    $casedKey = ucwords(str_replace("_", " ", $key));
    if(!is_numeric($casedKey)) echo "<b>$casedKey</b>";
    if(is_array($value)){
        echo "<ol>";
            foreach($value as $innerKey=>$innerValue){
                echo "<li>";
                $casedKey = ucwords(str_replace("_", " ", $innerKey));
                if(!is_numeric($casedKey)) echo "<b>$casedKey</b>";
                if(is_array($innerValue)){
                    echo "<ol>";
                    foreach($innerValue as $innerInnerKey=>$innerInnerValue){
                        echo "<li>";
                        $casedKey = ucwords(str_replace("_", " ", $innerInnerKey));
                        if(!is_numeric($casedKey)) echo "<b>$casedKey</b> ";
                        if(is_array($innerInnerValue)) print_r($innerInnerValue);
                        else
                            echo "= $innerInnerValue";
                        echo "</li>";
                    }
                    echo "</ol>";
                }else{
                    echo " = $innerValue";
                }
                echo "</li>";
            }
        echo "</ol>";
    }else{
        echo " = $value";
    }
    echo "</li>";

}
echo "</ul>";

echo "<a class='darker' href='?filing_history=$companyNumber'>View Filing History</a>";