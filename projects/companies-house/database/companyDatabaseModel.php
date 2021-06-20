<?php


class companyDatabaseModel
{
    private mysqli $database;
    public function __construct()
    {
        require "../../../server_details.php";
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }
    public function __destruct()
    {
        $this->database->close();
    }

    public function putCsvIntoDatabase(){
        $startTime = microtime(true);
        $wholeFileName = "dataProductDownload/BasicCompanyDataAsOneFile-2020-08-01.csv";
        $fileReference = fopen($wholeFileName, "r");
        $columnPositions = fgetcsv($fileReference);
        $keepingColumns = array("CompanyName", " CompanyNumber", "CompanyCategory", "CompanyStatus",
            "RegAddress.AddressLine1","RegAddress.AddressLine2", "RegAddress.County", "RegAddress.Country", "RegAddress.PostCode",
            "CountryOfOrigin", "IncorporationDate", "SICCode.SicText_1", "SICCode.SicText_2", "SICCode.SicText_3", "SICCode.SicText_4");
        $column = [];
        foreach($keepingColumns as $keepingColumn){
            $column[str_replace(".", "", trim($keepingColumn))] = array_search($keepingColumn, $columnPositions);
        }

        unset($keepingColumn);
        $companyCount = 0;
        $companyCountLimit = 200_000;
        echo "<ol>";
        if($bigFilePositionFile = file_get_contents("bigFilePosition.json")){
            $bigFilePosition = json_decode($bigFilePositionFile, true)["lastPosition"];
            if($bigFilePosition>0) // if its zero then just continue from the headers onwards
                fseek($fileReference, $bigFilePosition);
        }
        function getSicCodeFromText($sicText){
            if(strlen($sicText) == 0) return null;
            if(preg_match("/^([0-9A-Z]+) -/i", $sicText, $sicCode))
                return $sicCode[1];
            else
                return substr($sicText, 0, 5);
        }
        function govDateToTimeStamp($govDate){
            $timestamp = str_replace("/", "-", $govDate);
            $timestamp = strtotime($timestamp);
            $formattedDate = date("Y-m-d", $timestamp);
            return $formattedDate;
        }
        function capitalise($word){
            $lowerCase = strtolower($word);
            return ucwords($lowerCase);
        }
        
        $entryQuery = $this->database->prepare("INSERT INTO companies (name, number, streetAddress, county, country, 
                       postCode, origin, category, status, date, SicCode1, SicCode2, SicCode3, SicCode4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        while(!feof($fileReference)){
            if($companyCount>$companyCountLimit) break;
            if(feof($fileReference)) break;
            $companyRow = fgetcsv($fileReference);
            if(strlen($companyRow[$column["CompanyNumber"]])!=8) {
                echo "<li>Weird company number: (".$companyRow[$column["CompanyNumber"]].")</li>";
                continue;
            }
            if(strlen($companyRow[$column["RegAddressCounty"]])==0) $companyRow[$column["RegAddressCounty"]] = null;
            $entryQuery->bind_param("ssssssssssssss",
                $companyRow[$column["CompanyName"]],
                $companyRow[$column["CompanyNumber"]],
                capitalise($companyRow[$column["RegAddressAddressLine1"]]),
                capitalise($companyRow[$column["RegAddressCounty"]]),
                capitalise($companyRow[$column["RegAddressCountry"]]),
                $companyRow[$column["RegAddressPostCode"]],
                $companyRow[$column["CountryOfOrigin"]],
                $companyRow[$column["CompanyCategory"]],
                $companyRow[$column["CompanyStatus"]],
                govDateToTimeStamp($companyRow[$column["IncorporationDate"]]),
                getSicCodeFromText($companyRow[$column["SICCodeSicText_1"]]),
                getSicCodeFromText($companyRow[$column["SICCodeSicText_2"]]),
                getSicCodeFromText($companyRow[$column["SICCodeSicText_3"]]),
                getSicCodeFromText($companyRow[$column["SICCodeSicText_4"]])
                );
            if($entryQuery->execute())
                $companyCount++;
            else
                echo "<li>SQL execution error: $entryQuery->error</li>";
            if(microtime(true)-$startTime>100) break; // stop after 2 minutes
        }
        $entryQuery->close();
        $bigFilePosition = ftell($fileReference);
        file_put_contents("bigFilePosition.json", "{\"lastPosition\": $bigFilePosition}");
        $formattedCount = number_format($companyCount);
        echo "</ol>";
        $timeTaken = microtime(true) - $startTime;
        $minutesTaken = floor($timeTaken/60);
        $secondsTaken = str_pad($timeTaken%60, 2, "0", STR_PAD_LEFT);
        echo "<h3>Total companies extracted: $formattedCount in $minutesTaken:$secondsTaken</h3>";
    }
    public function getCompanyFromDatabase($companyNumber){
        $startTime = microtime(true);
        $companyNumber = str_pad($companyNumber, 8, "0", STR_PAD_LEFT);
        $sqlQuery = "SELECT * FROM companies WHERE number='$companyNumber' LIMIT 1";
        $result = $this->database->query($sqlQuery)->fetch_assoc();
//        $sqlQuery = "SELECT DISTINCT * FROM financials WHERE companyNumber='$companyNumber'";
//        $financialResults = $this->database->query($sqlQuery)->fetch_all(1);
//        foreach($financialResults as $financialResult){
//            $result["financials"][] = $financialResult;
//        }
        $sqlQuery = "SELECT DISTINCT firstName, lastName, birthMonth, birthYear FROM psc WHERE companyNumber='$companyNumber'";
        $peopleResults = $this->database->query($sqlQuery)->fetch_all(1);
        foreach($peopleResults as $peopleResult){
            $result["people"][] = $peopleResult;
        }
        $result["response_time"] = microtime(true) - $startTime;
        return $result;
    }
    public function getCompanyInfoFromDatabase($companyNumber): ?array
    {
        $companyNumber = str_pad($companyNumber, 8, "0", STR_PAD_LEFT);
        $sqlQuery = "SELECT * FROM companies WHERE number='$companyNumber' LIMIT 1";
        return $this->database->query($sqlQuery)->fetch_assoc();
    }
    public static function findSicCodeDescription($sicCode){
        $sicCodesFile = file_get_contents("../sic.json");
        $sicCodes = json_decode($sicCodesFile, true);
        if(isset($sicCodes[$sicCode]))
            return $sicCodes[$sicCode];
        elseif(isset($sicCodes[trim($sicCode, "0")]))
            return $sicCodes[trim($sicCode, "0")];
        else return "No description found";
    }

    public function getDetailsFromAccounts(){
        $startTime = microtime(true);
        $mainDirectory = "dataProductDownload/accounts";
        echo "<ol>";
        $successfullyAdded = 0;
        $netAssetsPreparedStatement = $this->database->prepare("UPDATE companies SET currentNetAssets=?,
                     previousNetAssets=?, filingDate=?, employees=?, accountsName=? WHERE number=?");
        if($this->database->error) foreach($this->database->error_list as $dbError) echo "<li>$dbError</li>";
        $companyNumber = "";
        foreach(scandir("$mainDirectory") as $account){
            if(!preg_match("/.html$/", $account)) continue;
            $htmlFile = file_get_contents("$mainDirectory/$account");
            $netAssetsPattern = "/NetAssetsLiabilities\"[^>]*>([0-9,]+)</m";
            preg_match_all($netAssetsPattern, $htmlFile, $netAssets);
            $employeesPattern = "/AverageNumberEmployeesDuringPeriod\"[^>]*>([0-9,]+)</m";
            preg_match_all($employeesPattern, $htmlFile, $employees);
            if(!isset($netAssets[1][0])&&!isset($employees[1][0])) continue;
            $employees = str_replace(",", "", $employees[1][0]??null);
            preg_match("/<xbrli:identifier scheme=\"http:\/\/www.companieshouse.gov.uk\/\">([0-9]+)<\/xbrli:identifier>/m", $htmlFile, $companyNumber);
            $companyNumber = $companyNumber[1];
            $assetsCurrent = str_replace(",", "", $netAssets[1][0]??null);
            $assetsPrevious = str_replace(",", "", $netAssets[1][1]??null);
            preg_match("/_([0-9]{4})([0-9]{2})([0-9]{2}).html$/", $account, $dateArray);
            $filingDate = strtotime($dateArray[3] . "-" . $dateArray[2] . "-" . $dateArray[1]);
            $filingDate = date("Y-m-d", $filingDate);
            $netAssetsPreparedStatement->bind_param("iisiss", $assetsCurrent, $assetsPrevious, $filingDate, $employees, $account, $companyNumber);
            if($netAssetsPreparedStatement->execute())
                $successfullyAdded++;
            else
                echo "<li style='font-size: larger'>$companyNumber failed to update: $netAssetsPreparedStatement->error</li>";
        }
        echo "</ol>";
        $netAssetsPreparedStatement->close();
        $finishTime = round(microtime(true) - $startTime, 3);
        echo "<p>Last company to be added: $companyNumber</p>";
        echo "<h3>Successfully updated $successfullyAdded companies net asset information in $finishTime seconds</h3>";
    } //TODO: Legacy function (uses preg_match)
    public function getDetailsFromAccountsXML(){
        $startTime = microtime(true);
        $mainDirectory = "dataProductDownload/accounts";
        $successfullyAdded = 0;
        $companyCount = 0;
        $statsReportGenerator = [];
        $financialsUpdateQuery = $this->database->prepare("INSERT INTO financials (companyNumber, date, label, 
                        value, unit, context) VALUES (?, ?, ?, ?, ?, ?)");
        echo "<ol>";
        foreach(scandir("$mainDirectory") as $account){
            if(!preg_match("/.html$/", $account)) continue;
//            if($successfullyAdded++>1) break;
            $htmlFile = file_get_contents("$mainDirectory/$account");
            $htmlFile = str_replace("nonnumeric", "nonNumeric", $htmlFile); //some are lowercase to begin (~10%)
            $htmlFile = str_replace("nonfraction", "nonFraction", $htmlFile); //not sure how much this will slow it down?
            $xmlFile = new SimpleXMLElement($htmlFile);
            preg_match("/(^[a-z0-9\-]+):/i", $xmlFile->xpath("//ix:nonNumeric")[0]["name"], $code);
            $code=$code[1];
            $balanceSheetDate = $xmlFile->xpath("//*[@name='$code:BalanceSheetDate']")[0];
            $companyNumberElement = $xmlFile->xpath("//*[@name='$code:UKCompaniesHouseRegisteredNumber']")[0];
            if(strlen((string)$companyNumberElement)) $companyNumber = (string)$companyNumberElement;
            else $companyNumber = $companyNumberElement->span;
            if(!strlen($companyNumber)) continue;
            if(!strtotime($balanceSheetDate)) continue;
            $dateFiled = date("Y-m-d", strtotime($balanceSheetDate));
            $companyNumber = str_pad($companyNumber, 8, "0", STR_PAD_LEFT);
            foreach($xmlFile->xpath("//ix:nonFraction") as $nonNumericElement){
                if(preg_match("/[a-z\-0-9]*:(.+)$/i", $nonNumericElement['name'], $fieldName)) {
                    $statsReportGenerator[$fieldName[1]]++;
                    $value = str_replace(",", "", (string)$nonNumericElement);
                    $label = $fieldName[1];
                    $unit = $nonNumericElement['unitRef'];
                    $contextRef = $nonNumericElement['contextRef'];
//                    var_dump(array("num"=>$companyNumber, "date"=>$dateFiled, "field"=>$label, "value"=>$value));
                    $financialsUpdateQuery->bind_param("sssiss", $companyNumber, $dateFiled, $label, $value, $unit, $contextRef);
                    if($financialsUpdateQuery->execute()) $successfullyAdded++;
                    else echo "<li><b>$companyNumber</b> failed - <sup>$financialsUpdateQuery->error</sup></li>";
                }
            }
            if(!$financialsUpdateQuery->error) unlink("$mainDirectory/$account"); //delete the file if it was successfully scanned
            $companyCount++;
        }
        echo "</ol>";
        $financialsUpdateQuery->close();
        arsort($statsReportGenerator);
//        echo "<ol>";
//        foreach($statsReportGenerator as $field=>$frequency){
//            $formattedFieldName = trim(preg_replace("/[A-Z][a-z]/", " $0", $field));
//            echo "<li>$formattedFieldName occurred $frequency times</li>";
//
//        }
//        echo "</ol>";
        $finishTime = round(microtime(true) - $startTime, 3);
//        echo "<p>Last company to be added: $companyNumber</p>";
        echo "<h3>Successfully updated $successfullyAdded fields for $companyCount companies in $finishTime seconds</h3>";
    }
    public function interpretContextFromAccounts(){
        $startTime = microtime(true);
        $sql = "SELECT DISTINCT *  FROM financials WHERE interpretation IS NULL ORDER BY RAND() LIMIT 15000";
        $selection = $this->database->query($sql)->fetch_all(1);
        $fieldsUpdated = 0;
        $failedFields = 0;
        $deletedFields = 0;
        $failedContexts = [];
        echo "<ol>";
        $preparedDeleteQuery = $this->database->prepare("DELETE FROM financials WHERE context=?");
        $preparedUpdateQuery = $this->database->prepare("UPDATE financials SET interpretation=? WHERE companyNumber=? AND label=? AND context=?");
        foreach($selection as $entry){
            $filingDate = $entry["date"];
            $context = $entry["context"];
            if(preg_match("/_?([0-3][0-9])_?([01][0-9])_?(20[0-9]{2})A?$/", $context, $correctDatePattern)){
                // use date pattern to find
                $date = $correctDatePattern[3] . "-" . $correctDatePattern[2] . "-" . $correctDatePattern[1];
            }elseif(preg_match("/CY(?!_START)|^companyA$|^B_?|FY1(?!.START)|[^t]_TMinusZ|".
                "Curr(?!.*Start)|PREVIOUS|CURRENT(?!.*START)|current|^C$|A$|^icur[0-9]/", $context)){ // CURRENT YEAR
                //date is not set, so check for current year & previous year tags
                $date = $filingDate;
            }elseif(preg_match("/PY|A_prev$|^E_|^E$|FY1.START|FY2|TMinusO|Comp|PREVIOUS|".
                "CURRENT_FY_START|Curr(?=.*Start)|^F$|^F_|CY_START|^iprev|Prev|PeriodStart_TMinusZero/", $context)){ //PREVIOUS YEAR
                $date = date("Y-m-d", strtotime($filingDate)-86400*365);
            }else {
//                echo "<li><b>$filingDate and $context could not find anything for ".$entry['companyNumber']."</b></li>";
                if(preg_match("/set[0-9]/i", $context)) {
                    $preparedDeleteQuery->bind_param("s", $context);
                    $preparedDeleteQuery->execute();
                    $deletedFields++;
                }else{
                    $failedFields++;
                    $failedContexts[$context]++;
                }
                continue;
            }
//            echo "<li>$filingDate and $context produced $date for ".$entry['companyNumber']."</li>";
            $preparedUpdateQuery->bind_param("ssss", $date, $entry["companyNumber"], $entry["label"], $context);
            if($preparedUpdateQuery->execute()){
                $fieldsUpdated++;
            }else{
                echo "<li>$date failed to enter the database</li>";
            }

        }
        $preparedDeleteQuery->close();
        $preparedUpdateQuery->close();
        echo "</ol>";
        $timetaken = round(microtime(true) - $startTime, 1);
        $successRate = round($fieldsUpdated/($fieldsUpdated+$failedFields) * 100, 1);
        echo "<h3>$timetaken seconds to update $fieldsUpdated fields. ($failedFields failed) ($successRate% success)</h3>";
        echo "<p>$deletedFields fields were deleted for containing 'set'</p>";
        arsort($failedContexts);
        echo "<ol>";
        foreach ($failedContexts as $failedContext=>$frequency){
            echo "<li>$failedContext wasn't recognised <b>$frequency</b> times</li>";
        }
        echo "</ol>";
    }
    public function getRandomCompanyFromDatabase()
    {
        $startTime = microtime(true);
        $sql = "SELECT number FROM companies ORDER BY RAND() LIMIT 1";
        $companyNumber = $this->database->query($sql)->fetch_assoc()["number"];
        $responseTime = microtime(true) - $startTime;
        echo round($responseTime, 4) . " seconds to find a random company<br>";
        return self::getCompanyFromDatabase($companyNumber);
    }
    public function getSampleCompany(){
        $startTime = microtime(true);
        $sql = "SELECT number FROM companies WHERE accountsName IS NOT NULL ORDER BY filingDate DESC LIMIT 100000";
        $allCompanies = $this->database->query($sql)->fetch_all(1);
        $companyNumber = $allCompanies[rand(0, count($allCompanies)-1)]["number"];
        $responseTime = microtime(true) - $startTime;
        echo round($responseTime, 4) . " seconds to find a random sample company<br>";
        return self::getCompanyFromDatabase($companyNumber);
    }
    public function getRandomCompanyWithAccounts(){
        return self::getRandomCompanyFromDatabase();
        $startTime = microtime(true);
        $sql = "SELECT DISTINCT companyNumber FROM financials ORDER BY RAND() LIMIT 1";
        $selection = $this->database->query($sql)->fetch_assoc();
        $responseTime = microtime(true) - $startTime;
        echo round($responseTime, 4) . " seconds to find a random sample company<br>";
        return self::getCompanyFromDatabase($selection["companyNumber"]);
    }
    public function getRandomCompanyNumberWithAccounts(){
        return self::getRandomCompanyFromDatabase()["number"];
        $sql = "SELECT DISTINCT companyNumber FROM financials ORDER BY RAND() LIMIT 1";
        $selection = $this->database->query($sql)->fetch_assoc();
        return $selection["companyNumber"];
    }
    public function getRandomCompanyNumbers($qty){
        $sql = "SELECT number FROM companies ORDER BY RAND() LIMIT $qty";
        $selection = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        return $selection;
    }
    public function getNumberOfDatabaseEntries(){
        $startTime = microtime(true);
        $sql = "SELECT COUNT(*) FROM companies";
        $companyCount = $this->database->query($sql)->fetch_assoc()["COUNT(*)"];
        echo "<div class='responseTime'>" . number_format(microtime(true)-$startTime, 4) . " seconds to count</div>";
        return $companyCount;
    }
    public function predictCompanyNumber($typed){
        if(preg_match("/^[01A-Z]/", $typed)){ // it starts with a 0, 1 or letter
            $sql = "SELECT number, name FROM companies WHERE number LIKE '$typed%'";
        }else{
            $sql = "SELECT number, name FROM companies WHERE number LIKE '0$typed%'";
        }
        $predictions = $this->database->query($sql)->fetch_all(1);
        return json_encode($predictions);
    }

    public function getCompanyList($limit=100, $sort="number"){
        $startTime = microtime(true);
        $sql = "SELECT * FROM companies ORDER BY $sort DESC LIMIT $limit";
        $results = $this->database->query($sql);
        if($this->database->error)
            return null;
        $resultsArray = $results->fetch_all(1);
        $resultsArray["response_time"] = microtime(true) - $startTime;
        return $resultsArray;
    } //TODO: Remove this legacy function

    public function getListOfFinancialLabels(){
        $sql = "SELECT label, COUNT(*) FROM financials WHERE interpretation IS NOT NULL GROUP BY label ORDER BY COUNT(*) DESC";
        $listOfLabels = $this->database->query($sql)->fetch_all(1);
        echo "<datalist id='financial-labels-list'>";
        foreach($listOfLabels as $label){
            if($label["COUNT(*)"]>250){
                $labelName = $label["label"];
                echo "<option value='$labelName'>$labelName</option>";
            }
        }
        echo "</datalist>";
        return $listOfLabels;
    }

    public function sortSearchByFinancial($financial, $order, $min, $max){
        $sql = "SELECT DISTINCT companyNumber FROM financials 
                WHERE `label`='$financial' AND `value` < '$max' AND `value` > '$min' ORDER BY `value` $order LIMIT 10";
        $results = $this->database->query($sql)->fetch_all(MYSQLI_NUM);
        $companies = self::convertCompanyNumbersToCompanies($results);
        return $companies;
    }
    public function convertCompanyNumbersToCompanies($companyNumbers, $preferredColumn=null){
        $companies = array();
        $columnFrequency = [];
        foreach ($companyNumbers as $companyNumber){
//            echo "<li>$companyNumber[0]</li>";
            $tempCompany = self::getCompanyFromDatabase($companyNumber[0]);
            foreach($tempCompany["financials"] as $financialEntry) {
                $columnFrequency[$financialEntry["label"]]++;
            }
            $companies[] = $tempCompany;
        }
        if(isset($preferredColumn)) $columnFrequency[$preferredColumn] = 1000000;
        arsort($columnFrequency);
        $companies["columns"] = $columnFrequency;
        return $companies;
    }
    public function countCompaniesWithAccounts(){
        $startTime = microtime(true);
        $sql = "SELECT DISTINCT companyNumber FROM financials";
        $companyCount = $this->database->query($sql)->num_rows;
        echo "<div class='responseTime'>" . number_format(microtime(true)-$startTime, 4) . " seconds to count</div>";
        return $companyCount;
    }
    public function getSicCodesForSelect(){
        // this JSON could be saved as a file and simply returned when called for, rather than calculating every time
        $file = fopen("../sic.csv", "r");
        fgets($file);
        $outputArray = new stdClass();
        while(!feof($file)){
            $nextLine = fgetcsv($file);
            $outputArray->{$nextLine[0]} = $nextLine[0]." - " . $nextLine[1] . "";
        }
        return json_encode((array)$outputArray);
    }
    public function searchCompaniesBySicCode($sicCode){
        $sql = "SELECT number FROM companies WHERE SicCode1='$sicCode' OR SicCode2='$sicCode' OR SicCode3='$sicCode' OR SicCode4='$sicCode' LIMIT 10";
        $results = $this->database->query($sql)->fetch_all(MYSQLI_NUM);
        $companies = self::convertCompanyNumbersToCompanies($results);
        return $companies;
    }
    public function searchCompaniesByPscAge($lowerBound, $upperBound)
    {
        $birthYearLowerBound = date("Y") - $upperBound;
        $birthYearUpperBound = date("Y") - $lowerBound;
        echo "<p>Companies with people born between $birthYearLowerBound and $birthYearUpperBound with significant control</p>";
        $sql = "SELECT companyNumber, birthYear, birthMonth FROM psc WHERE birthYear>=$birthYearLowerBound AND birthYear<=$birthYearUpperBound ORDER BY birthYear LIMIT 10";
        $results = $this->database->query($sql)->fetch_all(MYSQLI_NUM);
        echo $this->database->error;
        $companies = self::convertCompanyNumbersToCompanies($results);
        foreach($results as $result){
            foreach($companies as &$company){
                if($company["number"]==$result[0])
                $company["financials"][] = array("label"=>"Age of PSC", "value"=>(date("Y") - $result[1]));
            }
            unset($company);
        }
        $companies["columns"] = array("Age of PSC"=>10);
        return $companies;
    }
    public function unzipPersonsWithSignificantControlDownload()
    {
        $pscZipDownload = new ZipArchive();
        $personsFolder = scandir("dataProductDownload/persons");
        foreach($personsFolder as $personFile){
            if(is_dir("dataProductDownload/persons/$personFile")) continue;
            echo "<li>$personFile";
            if(preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})_.*\.zip/", $personFile, $dateArray)) {
                $fileName = "dataProductDownload/persons/$personFile";
                break;
            }
        }
        if(!isset($dateArray)) return;
        $year = $dateArray[1];
        $month = $dateArray[2];
        $day = $dateArray[3];
//        $fileName = "dataProductDownload/persons/persons-with-significant-control-snapshot-$year-$month-$day.zip";
//        $fileName = "dataProductDownload/persons/psc-snapshot-$year-$month-$day"."_1of17.zip";
        $pscZipDownload->open("$fileName");
        $extractionDirectory = "dataProductDownload/persons/extracted$year-$month-$day";
        if(!file_exists($extractionDirectory)) mkdir($extractionDirectory);
        if($pscZipDownload->extractTo($extractionDirectory)){
            unlink("$fileName");
            echo "<h3>Successfully unzipped (+deleted) file</h3><ol>";
            foreach(scandir($extractionDirectory) as $extractedFile){
                echo "<li>$extractedFile</li>";
            }
            echo "</ol>";
        }else{
            echo "<h3>Failed to extract file $fileName</h3><ol>";
        }
        $pscZipDownload->close();
    }

    public function scanTextFileForPersonsWithSignificantControl(){
        $startingTime = microtime(true);
        $fileName = "dataProductDownload/persons/latestSnapshot.txt";
        $fileReference = fopen($fileName, "r");
        if(file_exists("dataProductDownload/persons/latestFilePosition.txt")){
            $startingPosition = (int) file_get_contents("dataProductDownload/persons/latestFilePosition.txt");
            fseek($fileReference, $startingPosition);
        }
        $successCount = 0;
        $failedCount = 0;
        $preparedPSCquery = $this->database->prepare("INSERT INTO psc 
    (companyNumber, notified, ceased, kind, nationality, firstName, middleName, lastName, countryOfResidence, birthMonth,
     birthYear, link, natureOfControl1, natureOfControl2, natureOfControl3, natureOfControl4, natureOfControl5, addressLocality, addressPostCode,
     addressRegion, addressLineOne) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $personLimit = 200_000;
        while(!feof($fileReference)){
            if(microtime(true)-$startingTime> 120) break; // dont exceed two minutes
            $lineFromFile = fgets($fileReference);
            $dataFromFile = json_decode($lineFromFile, true);
            preg_match("/([^\/]+)$/", $dataFromFile["data"]["links"]["self"], $link);
            $preparedPSCquery->bind_param("sssssssssiissssssssss",
                $dataFromFile["company_number"],
                $dataFromFile["data"]["notified_on"],
                $dataFromFile["data"]["ceased_on"],
                $dataFromFile["data"]["kind"],
                $dataFromFile["data"]["nationality"],
                $dataFromFile["data"]["name_elements"]["forename"],
                $dataFromFile["data"]["name_elements"]["middle_name"],
                $dataFromFile["data"]["name_elements"]["surname"],
                $dataFromFile["data"]["country_of_residence"],
                $dataFromFile["data"]["date_of_birth"]["month"],
                $dataFromFile["data"]["date_of_birth"]["year"],
                $link[1],
                $dataFromFile["data"]["natures_of_control"][0],
                $dataFromFile["data"]["natures_of_control"][1],
                $dataFromFile["data"]["natures_of_control"][2],
                $dataFromFile["data"]["natures_of_control"][3],
                $dataFromFile["data"]["natures_of_control"][4],
                $dataFromFile["data"]["address"]["locality"],
                $dataFromFile["data"]["address"]["postal_code"],
                $dataFromFile["data"]["address"]["region"],
                $dataFromFile["data"]["address"]["address_line_1"]
            );
            if($preparedPSCquery->execute())
                $successCount++;
            else {
                $sqlFailures[] = $dataFromFile["company_number"];
                $failedCount++;
            }
            if($successCount+$failedCount>$personLimit) break;
        }
        if(feof($fileReference)) header("Location: ?");
        if(isset($sqlFailures)){
            echo "<p class='smallhead'>SQL errors</p>";
            echo "$failedCount persons are from companies that do not exist in the companies database";
            $failedCount = count($sqlFailures);
            $previouslyFailed = json_decode(file_get_contents("nonExistantCompanies.json"));
            $newList = (array_merge($sqlFailures, $previouslyFailed));
            file_put_contents("nonExistantCompanies.json", json_encode($newList));
        }
        $successCount = number_format($successCount);
        $failedCount = number_format($failedCount);
        file_put_contents("dataProductDownload/persons/latestFilePosition.txt", ftell($fileReference));
        echo "<h3>Successfully added $successCount persons. Failed to add $failedCount persons.</h3>";
    }
    public function deleteUnnecessaryFinancials(){
        $usefulOnes = array(
            "Equity",
            "Creditors",
            "NetCurrentAssetsLiabilities",
            "CurrentAssets",
            "TotalAssetsLessCurrentLiabilities",
            "AverageNumberEmployeesDuringPeriod",
            "NetAssetsLiabilities",
            "Debtors",
            "FixedAssets",
            "CashBankOnHand",
            "TotalInventories",
            "TradeCreditorsTradePayables",
            "CorporationTaxPayable",
            "TaxationSocialSecurityPayable",
            "IntangibleAssets",
            "ProfitLoss",
            "BankBorrowingsOverdraft",
            "AdvancesCreditsDirectors",
            "InvestmentsFixedAssets",
            "TurnoverRevenue",
            "AmountsOwedToDirectors",
            "InvestmentProperty"
        );
        $sql = "SELECT * FROM financials LIMIT 100000";
        $allCompanies = $this->database->query($sql)->fetch_all(1);
        $notUsefulOnes = [];
        foreach($allCompanies as $allCompany){
            $notUsefulOnes[$allCompany["label"]]++;
        }
        $difference = array_diff(array_keys($notUsefulOnes), $usefulOnes);
        $deleteStatement = $this->database->prepare("DELETE FROM financials WHERE label=?");
        foreach($difference as $unnecessaryLabel){
            $deleteStatement->bind_param("s", $unnecessaryLabel);
            if($deleteStatement->execute()) echo "<li>$unnecessaryLabel deleted</li>";
        }
        $deleteStatement->close();
    }
    public function deletedCeasedPersons(){
        $sql = "DELETE FROM psc WHERE ceased IS NOT NULL";
        $this->database->query($sql);
    }
    public function getPersonsInCompany($companyNumber){ // I did this inline instead (see getCompanyFromDatabase)
        $sql = "SELECT * FROM psc WHERE companyNumber='$companyNumber'";
        $people = $this->database->query($sql)->fetch_all(1);
        return $people;
    }
    public function searchByPerson($searchTerm){
        $birthYearQuery = "ORDER BY birthYear LIMIT 25";
        if(preg_match("/[0-9]{4}/", $searchTerm, $birthYear)) {
            $birthYearQuery = "AND birthYear='" . $birthYear[0] . "' ORDER BY birthMonth LIMIT 25";
            $searchTerm = str_replace($birthYear[0], "", $searchTerm);
        }
        $nameSplitUp = explode(" ", trim($searchTerm));
        $firstName = $nameSplitUp[0];
        $lastName = end($nameSplitUp);
        if($firstName==$lastName){
            $sql = "SELECT DISTINCT firstName, lastName, birthMonth, birthYear FROM psc WHERE (firstName='$firstName' OR lastName='$lastName') $birthYearQuery";
        }else{
            $sql = "SELECT DISTINCT firstName, lastName, birthMonth, birthYear FROM psc WHERE firstName='$firstName' AND lastName='$lastName' $birthYearQuery";
        }
        $results = $this->database->query($sql)->fetch_all(1);
        return $results;
    }

    public function getCompanyListByPerson($firstName, $lastName, $birthMonth, $birthYear)
    {
        $sql = "SELECT * FROM psc WHERE firstName='$firstName' AND lastName='$lastName' 
                                AND birthMonth='$birthMonth' AND birthYear='$birthYear'";
        $matches = $this->database->query($sql)->fetch_all(2);
        $companies = $this->convertCompanyNumbersToCompanies($matches);
        $personDetails = [];

        $labels = array_keys($this->database->query("SELECT * FROM psc WHERE link='___uay-NDQ50L81XSZot63y7MzM' LIMIT 1")->fetch_assoc());

        foreach($matches as $match){
            foreach($match as $label=>$value){
                $personDetails[$labels[$label]][$value]++;
            }
        }
        $finalArray = array("companies"=>$companies, "person"=>$personDetails);
        return $finalArray;
    }

    public function getPscDetailsForCompanies($listOfCompanyNumbers)
    {
        //TODO: For each company number, call the companies house API to get all available details like type of control
        foreach($listOfCompanyNumbers as $companyNumber){
            $paddedNumber = str_pad($companyNumber, 8, "0", STR_PAD_LEFT);
            $SQL = "SELECT * FROM psc WHERE companyNumber='$paddedNumber'";
            $tempResults = $this->database->query($SQL)->fetch_all(MYSQLI_ASSOC);
            foreach($tempResults as $tempResult){
                $results[] = $tempResult;
            }
        }
        return $results??[];
    }
    public static function getPscDetailsFromAPI($link){
        $url = "api.company-information.service.gov.uk";
        $request = curl_init($url);

    }
    public static function capitaliseLabel($label){
        $spaced = preg_replace("/[A-Z][a-z]/", " $0", $label);
        return ucfirst($spaced);
    }

}