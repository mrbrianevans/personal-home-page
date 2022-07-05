<?php
$transactionId = $_GET["transaction_id"];
$companyNumber = $_GET["company_number"];
$companyRequest = curl_init("https://api.companieshouse.gov.uk/company/$companyNumber/filing-history/$transactionId");
curl_setopt($companyRequest, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($companyRequest, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
curl_setopt($companyRequest, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($companyRequest);
curl_close($companyRequest);
$result = json_decode($result, true);

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

echo "Metadata: <br>";
$metadataURL = "https://frontend-doc-api.companieshouse.gov.uk/document/vTQkJdTmPIAg6Kj8qb3NGvQ-2WAdSF1G5E_T8Gkbsps";
$companyRequest = curl_init($metadataURL);
curl_setopt($companyRequest, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($companyRequest, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
curl_setopt($companyRequest, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($companyRequest);
curl_close($companyRequest);
$result = json_decode($result, true);

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

if(!filesize("../documents/$transactionId.pdf")){
    $documentURL = $result["links"]["document"];
    $companyRequest = curl_init($documentURL);
    curl_setopt($companyRequest, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($companyRequest, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
    curl_setopt($companyRequest, CURLOPT_TIMEOUT, 20);
    curl_setopt($companyRequest, CURLOPT_FOLLOWLOCATION, true);
    $fileHandler = fopen("../documents/$transactionId.pdf", "w+");
    curl_setopt($companyRequest, CURLOPT_FILE, $fileHandler);
    curl_exec($companyRequest);
    curl_close($companyRequest);
    fclose($fileHandler);
    switch(curl_getinfo($companyRequest, CURLINFO_HTTP_CODE)){
        case 200:
            echo "Successfully downloaded file at " . date("g:ia");
            echo "<br>";
            echo "File size: " . number_format(filesize("../documents/$transactionId.pdf")) . " bytes";
            break;
        case 302:
            echo "Too many redirects to download file";
            break;
        case 406:
            echo "Wrong file type, could not download";
            break;
        case 400:
            echo "Invalid parameter";
            break;
        default:
            echo "Unknown error occurred " . curl_getinfo($companyRequest, CURLINFO_HTTP_CODE) . ".";
            break;
    }
}else{
    echo "File was downloaded on " . date("j M Y", filemtime("../documents/$transactionId.pdf"));
    echo "<br>";
    echo "File size: " . number_format(filesize("../documents/$transactionId.pdf")) . " bytes";
}
