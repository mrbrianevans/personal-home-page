<?php


class crawlingCompaniesModel
{
    private array $queriesQueried ;
    private array $companies;
    private stdClass $companiesSTD;
    private mysqli $database;
    public function __construct()
    {
        require "../../../db_conn.php";
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }
    public function __destruct()
    {
        $this->database->close();
    }

    public function runAlphabetCrawl()
    {
//        echo "<meta http-equiv=\"refresh\" content=\"3\"/>";
        $crawlStartTime = microtime(true);

        $queriesToQuery = json_decode(file_get_contents("queriesToQuery.json"));// gets list of queries from file
        $queriesQueried = json_decode(file_get_contents("queriesQueried.json"));
        $queries = array_unique(array_diff($queriesToQuery, $queriesQueried));//This stops it repeating queries its already done
        file_put_contents("queriesToQuery.json", json_encode(array_values($queries))); // save the queriesToQuery file with no duplicates. This seems to have problems
        if(count($queries)==0) header("Location: /projects/companies-house/crawler");
        shuffle($queries);
        $queries = array_slice($queries, 0, 10); // takes the top 8 queries (should be less than 60 seconds)

        $companiesAdded = 0;

        foreach($queries as $query){
            $queryStartTime = time();
            $results = self::getSearchResults($query);
            foreach($results as $result){
                $companyNumber = $result["company_number"];
                $this->companiesSTD->{"$companyNumber"} = $result;
            }

            $queryFinishTime = time();
            $queryTimeTaken = $queryFinishTime - $queryStartTime;
            echo "<br>Query $query took $queryTimeTaken seconds to complete. ";
        }
        $this->queriesQueried = array_merge(json_decode(file_get_contents("queriesQueried.json")), $queries);
        $this->queriesQueried = array_unique($this->queriesQueried);
        file_put_contents("queriesQueried.json", json_encode($this->queriesQueried)); //this seems to work fine
        unset($this->queriesQueried);
        echo "<br>End of queries";

        $this->companies = (array) $this->companiesSTD; // this allows numeric string keys apparently
        echo "<br>Sample of this searches array: <br>";
        print_r(array_slice($this->companies, 0, 1));
        $fileSize = file_put_contents("companies/legacy/companyNameDatabaseTemp".time().".json", json_encode($this->companies));

        $actualEndCount = count($this->companies);
        $actualEndCount = number_format($actualEndCount);
        unset($companiesEnd);

        echo "<br><br>File size finished at " . number_format($fileSize) . " bytes";
        echo "<br>Total company count is $actualEndCount companies";

        $requestTime = round(microtime(true)-$crawlStartTime, 2);
        echo "<br><br>Total crawl took $requestTime seconds to complete " . count($queries) . " queries";
        $minimumLimitTime = count($queries) * 5;
        $remainingTime = (int)($minimumLimitTime - $requestTime)+1;
        if($remainingTime > 0){
            echo "<br>Sleeping for $remainingTime seconds to prevent rate limiting(not)";
//            sleep($remainingTime);
        }

    }

    /**
     * @param $query string Query to search for
     * @return array filtered array of results for query:= array(0=>array("company_number"=>1), 1=>array("title"=>"name"))
     */
    public static function getSearchResults($query)
    {
        $request = curl_init("https://api.companieshouse.gov.uk/search/companies?q=$query&items_per_page=100");
        curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($request, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($request);
        curl_close($request);
        $result = json_decode($result, true);
        $allResultingItems = $result["items"];
        for($i=100;$i<2000;$i+=100){
            $request = curl_init("https://api.companieshouse.gov.uk/search/companies?q=$query&items_per_page=100&start_index=$i");
            curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($request, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($request);
            curl_close($request);
            $result = json_decode($result, true);
            if(count($result["items"])==0) break;
            $allResultingItems = array_merge($allResultingItems, $result["items"]);
        }
        $keepingColumns = array("title", "date_of_creation", "company_type", "company_status", "address", "company_number");
        $returnResults = array();
        foreach($allResultingItems as $item){
            $singleResult = [];
            foreach($keepingColumns as $column){
                $singleResult[$column] = $item[$column];
            }
            $returnResults[] = $singleResult;
        }
        return $returnResults;
    }

    public function getFreeCompanyDataProduct(){
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
        $savingArray = array();
        $companyCount = 0;
        $companyCountLimit = 2_080_000; // usually it can do 280,000 in 2 minutes
        echo "<ol>";
        if($bigFilePositionFile = file_get_contents("bigFilePosition.json")){
            $bigFilePosition = json_decode($bigFilePositionFile, true)["lastPosition"];
            fseek($fileReference, $bigFilePosition);
        }
        $formattedCount = "";
        $companiesInEachFile = 20_000; // generally 20,000 works well
        while(!feof($fileReference)){
            if($companyCount>$companyCountLimit) break;
            foreach(range(1, $companiesInEachFile) as $lineNumber) {
                if(feof($fileReference)) break;
                $companyRow = fgetcsv($fileReference);
                if(strlen($companyRow[$column["CompanyNumber"]])!=8) {
                    echo "<li>Weird company number: (".$companyRow[$column["CompanyNumber"]].")</li>";
                    continue;
                }
                $companyCount++;
                foreach ($column as $columnName => $columnNumber) {
                    if (strlen($companyRow[$columnNumber])>0)
                        $savingArray["".$companyRow[$column["CompanyNumber"]].""][trim($columnName)] = $companyRow[$columnNumber];
                }
            }
            $time = time();
            $fileSize = file_put_contents("companies/uncategorised/dataProductBatch$time.json", json_encode($savingArray));
            $bigFilePosition = ftell($fileReference);
            file_put_contents("bigFilePosition.json", "{\"lastPosition\": $bigFilePosition}");
            $fileSize = number_format($fileSize);
            $date = date("g:i:s");
            $formattedCount = number_format($companyCount);
            echo "<li>At $date, $fileSize bytes written to file. $formattedCount companies saved so far</li>";
            $savingArray = array();
            if(microtime(true)-$startTime>120) break; // stop after 2 minutes
        }
        echo "</ol>";
        echo "<h3>Total companies extracted: $formattedCount</h3>";
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
        if($companyCount==0) header("Location: /projects/companies-house/crawler");
    }
    public function getCompanyFromDatabase($companyNumber){
        $startTime = microtime(true);
        $sqlQuery = "SELECT * FROM companies WHERE number='$companyNumber' LIMIT 1";
        $result = $this->database->query($sqlQuery)->fetch_assoc();
        $result["response_time"] = microtime(true) - $startTime;
        return $result;
    }
    public function getUncategorisedFigures(){
        $uncatFiles = scandir("companies/uncategorised");
        $files = count($uncatFiles)-2;
        if($files){
            $numberInEachFile = count(json_decode(file_get_contents("companies/uncategorised".$uncatFiles[3]), true));
            $unsorted = number_format($files*$numberInEachFile);
            $files = number_format($files);
            echo "$unsorted companies are still unsorted, and waiting in $files files";
        }else
            echo "No files waiting in queue";
    }
    public function sortByCompanyNumber()
    {

        $startTime = microtime(true);
        $companyNumberCodes = [];
        $limit = 0;
        $companiesSorted = 0;
        foreach(scandir("companies/uncategorised") as $companyDatabaseFilename){
            if(!preg_match("/.json$/", $companyDatabaseFilename)) continue;
            if($limit++==4) break;
            $fullFilePath = "companies/uncategorised/$companyDatabaseFilename";
            $companyDatabaseArray = json_decode(file_get_contents($fullFilePath), true);
            foreach($companyDatabaseArray as $companyNumber=>$companyDetails){
                $code = substr("$companyNumber", 0, 4);
                $companyNumberCodes[""."$code"][""."$companyNumber".""] = $companyDetails;
                $companiesSorted++;
            }
            unlink($fullFilePath); // delete file if its been sorted
        }
        // UP UNTIL THIS POINT takes about 1 second for every 2,500 companies
        $folderCount = count($companyNumberCodes);
        $companiesSorted = number_format($companiesSorted);
        echo "Attempting to write $companiesSorted new companies to their $folderCount individual files<ol>";
        foreach($companyNumberCodes as $companyNumberCode=>$companies){
            $category = substr($companyNumberCode, 0, 2);
            if(!file_exists("companies/tempCategories/$category/$companyNumberCode")) mkdir("companies/tempCategories/$category/$companyNumberCode");
            $time = microtime();
            $fileSize = file_put_contents("companies/tempCategories/$category/$companyNumberCode/$time.json", json_encode($companies));
            $fileSize = number_format($fileSize);
            $companyCount = number_format(count($companies));
            $memoryUsage = number_format(memory_get_usage());
            $timeSoFar = round(microtime(true) - $startTime, 1);
            $minutesSoFar = floor($timeSoFar/60);
            $secondsSoFar = str_pad($timeSoFar%60, 2, "0", STR_PAD_LEFT);
            echo "<li><b>$minutesSoFar:$secondsSoFar</b> - $fileSize bytes written for code $companyNumberCode with $companyCount companies. Memory usage: $memoryUsage bytes</li>";
        }
        $finishTime = microtime(true);
        $timeTaken = round($finishTime - $startTime, 3);
        echo "</ol><h2>$timeTaken seconds taken to primary sort $companiesSorted companies</h2>";

    }
    public function secondarySort(){
        $startTime = microtime(true);
        $limit = 1;
        $companyLimit = 12000;
        $companyCount = 0;
        echo "<ol>";
        foreach(scandir("companies/tempCategories") as $twoDigitCategory){
            if(!preg_match("/^[a-z0-9]+$/i", $twoDigitCategory)) continue;
            foreach(scandir("companies/tempCategories/$twoDigitCategory") as $fourDigitCode){
                if(!preg_match("/^[a-z0-9]+$/i", $fourDigitCode)) continue;
                foreach(scandir("companies/tempCategories/$twoDigitCategory/$fourDigitCode") as $tempFileName){
                    if(!preg_match("/.json$/", $tempFileName)) continue;
                    $tempFileContents = file_get_contents("companies/tempCategories/$twoDigitCategory/$fourDigitCode/$tempFileName");
                    $tempFileArray = json_decode($tempFileContents, true);
                    $previousRecords = file_get_contents("companies/categories/$twoDigitCategory/$fourDigitCode.json");
                    $previousArray = json_decode($previousRecords, true);
                    $combinedArray = [];
                    foreach($tempFileArray as $companyNumber=>$companyDetails){
                        $combinedArray[$companyDetails["CompanyNumber"]] = $companyDetails;
                    }
                    unset($companyNumber, $companyDetails);
                    foreach($previousArray as $companyNumber=>$companyDetails){
                        $combinedArray["".$companyDetails["CompanyNumber"]] = $companyDetails;
                    }
                    file_put_contents("companies/categories/$twoDigitCategory/$fourDigitCode.json", json_encode($combinedArray));
                    $newCompanies = count($tempFileArray);
                    $oldCompanies = count($previousArray);
                    $totalCompanies = count($combinedArray);
                    $actualNewCompanies = $totalCompanies - $oldCompanies;
                    $timeTaken = round(microtime(true) - $startTime);
                    unlink("companies/tempCategories/$twoDigitCategory/$fourDigitCode/$tempFileName"); // only delete the file if it successfully sorted it
                    $companyCount+=$actualNewCompanies;
                    echo "<li>$timeTaken seconds: $newCompanies new companies added to the $oldCompanies companies already in $fourDigitCode.json file to make $totalCompanies total companies.</li>";
                    if($limit++>=$companyLimit) break;
                } // finish a category of temp files so it only has to deal with each final file once
                $timeTaken = ceil(microtime(true) - $startTime);
                if($timeTaken>100) $limit = $companyLimit; // dont wait longer than two minutes. Hit the limit if so
                if($limit>=$companyLimit) break;
            }
            if($limit>=$companyLimit) break;
        }
        $timeTaken = round(microtime(true) - $startTime, 3);
        $companyCount = number_format($companyCount);
        echo "</ol><h2>$timeTaken seconds taken to secondary sort and add $companyCount new companies</h2>";
    }
    public function sortByCompanyNumberLegacy()
    {

        $startTime = microtime(true);
        $companyNumberCodes = [];
        $limit = 0;
        $companiesSorted = 0;
        foreach(scandir("companies/uncategorised") as $companyDatabaseFilename){
            if(!preg_match("/.json$/", $companyDatabaseFilename)) continue;
            if($limit++==100) break;
            $fullFilePath = "companies/uncategorised/$companyDatabaseFilename";
            $companyDatabaseArray = json_decode(file_get_contents($fullFilePath), true);
            foreach($companyDatabaseArray as $companyNumber=>$companyDetails){
                $code = substr("$companyNumber", 0, 4);
                $companyNumberCodes[""."$code"][""."$companyNumber".""] = $companyDetails;
                $companiesSorted++;
            }
            rename($fullFilePath, "companies/categorised/$companyDatabaseFilename");
        }
        $folderCount = count($companyNumberCodes);
        $companiesSorted = number_format($companiesSorted);
        echo "Attempting to write $companiesSorted new companies to their $folderCount individual files<ol>";
        foreach($companyNumberCodes as $companyNumberCode=>$companies){
            $category = substr($companyNumberCode, 0, 2);
            if(!file_exists("companies/categories/$category")) mkdir("companies/categories/$category");
            if(file_exists("companies/categories/$category/$companyNumberCode.json"))//if the file already exists, then don't throw out the previous records
                $companies = array_merge(json_decode(file_get_contents("companies/categories/$category/$companyNumberCode.json"), true), $companies);
            $fileSize = file_put_contents("companies/categories/$category/$companyNumberCode.json", json_encode($companies));
            $fileSize = number_format($fileSize);
            $companyCount = number_format(count($companies));
            $memoryUsage = number_format(memory_get_usage());
            $timeSoFar = round(microtime(true) - $startTime, 1);
            $minutesSoFar = floor($timeSoFar/60);
            $secondsSoFar = str_pad($timeSoFar%60, 2, "0", STR_PAD_LEFT);
            echo "<li><b>$minutesSoFar:$secondsSoFar</b> - $fileSize bytes written for code $companyNumberCode with $companyCount companies. Memory usage: $memoryUsage bytes</li>";
        }
        $finishTime = microtime(true);
        $timeTaken = round($finishTime - $startTime, 3);
        echo "</ol><h2>$timeTaken seconds taken to sort $companiesSorted companies</h2>";

    }
    public function findBrokenFiles(){
        echo "<ol>";
        foreach(scandir("companies/categories") as $companyDatabaseCategory){
            if(!preg_match("/^[a-z0-9]+$/i", $companyDatabaseCategory)) continue;
            $correctFiles = 0;
            foreach(scandir("companies/categories/$companyDatabaseCategory") as $companyDatabaseFilename){
                if(!preg_match("/.json$/", $companyDatabaseFilename)) continue;
                $fileContents = file_get_contents("companies/categories/$companyDatabaseCategory/$companyDatabaseFilename");
                if(is_null(json_decode($fileContents)))
                    echo "<li style='font-size: large'><b>Error with file $companyDatabaseFilename</b></li>";
                else $correctFiles++;
            }
            echo "<li>$correctFiles correct files in folder $companyDatabaseCategory</li>";
        }
        echo "</ol>";
    }
    public function fixKeysOfSortedFiles(){
        echo "<ol>";
        foreach(scandir("companies/categories") as $companyDatabaseCategory){
            if(!preg_match("/^[a-z0-9]+$/i", $companyDatabaseCategory)) continue;
            foreach(scandir("companies/categories/$companyDatabaseCategory") as $companyDatabaseFilename){
                if(!preg_match("/.json$/", $companyDatabaseFilename)) continue;
                if($this->fixKeysOfSpecificFile($companyDatabaseFilename, true))
                    echo "<li>Successfully rewrote $companyDatabaseFilename</li>";
            }
        }
        echo "</ol>";
    }
    public function resetAllCategorisedPages(){
        $listOfFiles = scandir("companies/categorised");
        shuffle($listOfFiles);
        foreach($listOfFiles as $page){
            if(!preg_match("/.json$/", $page)) continue;
            rename("companies/categorised/$page", "companies/uncategorised/$page");
        }
    }
    public function fixKeysOfSpecificFile($fileName, $onlyCheckFirstLine=true){
        $categoryCode = substr($fileName, 0, 2);
        $fullFilePath = "companies/categories/$categoryCode/$fileName";
        $companyDatabaseArray = json_decode(file_get_contents($fullFilePath), true);
        if($onlyCheckFirstLine){
            foreach($companyDatabaseArray as $companyNumber=>$companyDetails){
                if(isset($companyDetails["CompanyNumber"]) && $companyNumber==$companyDetails["CompanyNumber"])
                    return false;
            }
            unset($companyNumber, $companyDetails);
        }

        $newCompanyArray = [];
        foreach($companyDatabaseArray as $companyNumber=>$companyDetails){
            $compNum = $companyDetails["CompanyNumber"];
            if(strlen($compNum)!=8) echo "<li>Error</li>";
            else $newCompanyArray[""."$compNum".""] = $companyDetails;
            unset($companyNumber, $companyDetails);
        }
        file_put_contents($fullFilePath, json_encode($newCompanyArray));
        return true;
    }
    public function fixKeysInCategory($category){
        echo "<ol>";
            foreach(scandir("companies/categories/$category") as $companyDatabaseFilename){
                if(!preg_match("/.json$/", $companyDatabaseFilename)) continue;
                if($this->fixKeysOfSpecificFile($companyDatabaseFilename, false))
                    echo "<li>Successfully rewrote $companyDatabaseFilename</li>";
            }
        echo "</ol>";
    }
    public static function findCompany($companyNumber)
    {
        $startTime = microtime(true);
        switch (strlen($companyNumber)){
            case 7:
                $companyNumber = "0$companyNumber";
                break;
            case 8:
                break;
            default:
                return false;
        }
        $companyCode = substr($companyNumber, 0, 2);
        $fileCode = substr($companyNumber, 0, 4);
        $parentFile = file_get_contents("companies/categories/$companyCode/$fileCode.json");
        $parentArray = json_decode($parentFile, true);
        if(isset($parentArray[$companyNumber])){
            $requestedCompany = $parentArray[$companyNumber];
            $finishTime = microtime(true);
            $requestedCompany["response_time"] = $finishTime-$startTime;
            return $requestedCompany;
        }else{
            self::addQueryToQuery($companyNumber);
            return false;
        }
    }
    public function findCompanyUsingAPI($companyNumber){
        $startTime = microtime(true);
        switch (strlen($companyNumber)){
            case 7:
                $companyNumber = "0$companyNumber";
                break;
            case 8:
                break;
            default:
                return false;
        }
        $request = curl_init("https://api.companieshouse.gov.uk/company/$companyNumber");
        curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($request, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($request);
        curl_close($request);
        $requestedCompany = json_decode($result, true);
        // Change the keys to the match the ones in the display unit
        $filteredCompanyArray = [];
        $filteredCompanyArray["CompanyNumber"] = $requestedCompany["company_number"];
        $filteredCompanyArray["CompanyStatus"] = $requestedCompany["company_status"];
        $filteredCompanyArray["CompanyCategory"] = $requestedCompany["type"];
        $filteredCompanyArray["CompanyName"] = $requestedCompany["company_name"];
        $filteredCompanyArray["IncorporationDate"] = $requestedCompany["date_of_creation"];
        $filteredCompanyArray["RegAddressCountry"] = $requestedCompany["registered_office_address"]["country"];
        $filteredCompanyArray["RegAddressPostCode"] = $requestedCompany["registered_office_address"]["postal_code"];
        $filteredCompanyArray["RegAddressCounty"] = $requestedCompany["registered_office_address"]["region"]??$requestedCompany["registered_office_address"]["locality"];
        foreach($requestedCompany["sic_codes"] as $index=>$sic_code){
            $sicCodeNumber = $index+1;
            $filteredCompanyArray["SICCodeSicText_$sicCodeNumber"] = $sic_code . " - " . $this->findSicCodeDescription($sic_code);
        }

        $finishTime = microtime(true);
        $filteredCompanyArray["response_time"] = $finishTime-$startTime;
        echo "Companies House API requested";
        return $filteredCompanyArray;
    }
    private function findSicCodeDescription($sicCode){
        $sicCodesFile = file_get_contents("../sic.json");
        $sicCodes = json_decode($sicCodesFile, true);
        if(isset($sicCodes[$sicCode]))
            return $sicCodes[$sicCode];
        elseif(isset($sicCodes[trim($sicCode, "0")]))
            return $sicCodes[trim($sicCode, "0")];
        else return "No description found";
    }
    public function findRandomCompanyNumber(){
        $allFolders = scandir("companies/categories");
        $folderName = $allFolders[rand(2, count($allFolders)-1)];
        $allFiles = scandir("companies/categories/$folderName");
        $fileName = $allFiles[rand(2, count($allFiles)-1)];
        $fileContents = file_get_contents("companies/categories/$folderName/$fileName");
        $allCompanies = json_decode($fileContents, true);
        $allCompanyNumbers = array_keys($allCompanies);
        $companyNumber = $allCompanyNumbers[rand(0, count($allCompanyNumbers)-1)];
        return $companyNumber;
    }
    public function generateRandomWeightedCompanyNumber(){
        $allFolders = scandir("companies/categories");
        $allFiles = [];
        $randomFileNumber = rand(1, 9000);
        foreach($allFolders as $folder){
            if(!preg_match("/^[a-z0-9]+$/i", $folder)) continue;
            $files = scandir("companies/categories/$folder");
            foreach($files as $file) {
                if (is_file("companies/categories/$folder/$file")) {
                    $fileSizeWeighting = ceil(filesize("companies/categories/$folder/$file") / 50000);
                    $randomFileNumber -= $fileSizeWeighting;
                    if($randomFileNumber <= 0){
                        $fileContents = file_get_contents("companies/categories/$folder/$file");
                        $allCompanies = json_decode($fileContents, true);
                        $allCompanyNumbers = array_keys($allCompanies);
                        $companyNumber = $allCompanyNumbers[rand(0, count($allCompanyNumbers)-1)];
                        return $companyNumber;
                    }
                }
            }
        }
        return false;
    }
    public function listSortedFiles(){
        $files = [];
        $companiesNumber = [];
        echo "<ol>";
        foreach(scandir("companies/categories") as $companyDatabaseCategory){
            if(!preg_match("/^[a-z0-9]+$/i", $companyDatabaseCategory)) continue;
            foreach(scandir("companies/categories/$companyDatabaseCategory") as $companyDatabaseFilename){
                if(!preg_match("/.json$/", $companyDatabaseFilename)) continue;
                $files[$companyDatabaseFilename] = filesize("companies/categories/$companyDatabaseCategory/$companyDatabaseFilename");
                try{
                    $companiesNumber[$companyDatabaseFilename] = count(json_decode(file_get_contents("companies/categories/$companyDatabaseCategory/$companyDatabaseFilename"), true));
                }catch (Exception $exception){
                    echo "<li>Failed to convert file $companyDatabaseFilename to JSON : $exception</li>";
                }

            }
        }
        echo "</ol>";
        arsort($files);
        $numberOfFiles = count($files);
        $totalSize = number_format(array_sum($files));
        $totalCompanies = number_format(array_sum($companiesNumber));
        echo "Total of $numberOfFiles files containing a total of $totalSize bytes and <b>$totalCompanies</b> companies";
        echo "<ol>";
        foreach($files as $file=>$size){
            $number = $companiesNumber[$file];
            echo "<li>$file - ";
            echo number_format($size);
            echo " bytes and $number companies</li>";
        }
        echo "</ol>";
    }
    public function countStoredCompanies(){
        $categoryCompanyCount = [];
        foreach(scandir("companies/categories") as $companyDatabaseCategory){
            if(!preg_match("/^[a-z0-9]+$/i", $companyDatabaseCategory)) continue;
            $categoryCompanyCount[$companyDatabaseCategory] = 0;
            foreach(scandir("companies/categories/$companyDatabaseCategory") as $companyDatabaseFilename){
                if(!preg_match("/.json$/", $companyDatabaseFilename)) continue;
                $fileContents = file_get_contents("companies/categories/$companyDatabaseCategory/$companyDatabaseFilename");
                $categoryCompanyCount[$companyDatabaseCategory] += count(json_decode($fileContents, true));
            }
        }
        $totalCompanyCount = number_format(array_sum($categoryCompanyCount));
        echo "<h3>$totalCompanyCount companies stored</h3><ol>";
        foreach($categoryCompanyCount as $category=>$count){
            $formattedCount = number_format($count);
            echo "<li>Category $category - $formattedCount companies</li>";
        }
        echo "</ol>";
    }
    public static function addQueryToQuery($query){
        $currentQueryQueue = json_decode(file_get_contents("queriesToQuery.json"));
        $currentQueryQueue[] = $query;
        file_put_contents("queriesToQuery.json", json_encode($currentQueryQueue));
    }
    public function viewQueryQueue(){
        $currentQueryQueue = json_decode(file_get_contents("queriesToQuery.json"));
        echo "<ol>";
        foreach($currentQueryQueue as $query) echo "<li>$query</li>";
        echo "</ol>";
    }

    public function getEmployeesFromAccounts(){
        $pattern = "/core:NetAssetsLiabilities[^>]+>(0-9,)</m";
    }
    public function getNetAssetsFromAccounts(){
        $mainDirectory = "dataProductDownload/accounts";
        foreach(scandir("$mainDirectory") as $account){
            if(!preg_match("/.html$/", $account)) continue;
            $htmlFile = file_get_contents("$mainDirectory/$account");
            $netAssetsPattern = "/name=\"core:NetAssetsLiabilities\"[^>]*>([0-9,]+)</m";
            preg_match_all($netAssetsPattern, $htmlFile, $netAssets);
            preg_match("/<xbrli:identifier scheme=\"http:\/\/www.companieshouse.gov.uk\/\">([0-9]+)<\/xbrli:identifier>/m", $htmlFile, $companyNumber);
            $companyNumber = $companyNumber[1];
            echo "<br>$companyNumber - Filed on ";
            $assetsCurrent = $netAssets[1][0];
            $assetsPrevious = $netAssets[1][1];
            preg_match("/_([0-9]{4})([0-9]{2})([0-9]{2}).html$/", $account, $dateArray);
            $filingDate = strtotime($dateArray[3] . "-" . $dateArray[2] . "-" . $dateArray[1]);
            echo date("j M Y", $filingDate);
            $filingYear = $dateArray[1];
            echo " - $filingYear assets were $assetsCurrent";
//            print_r($netAssets[1]);
        }

    }

    public function getRandomCompanyFromDatabase()
    {
        $startTime = microtime(true);
        $sql = "SELECT number FROM companies";
        $allCompanies = $this->database->query($sql)->fetch_all(1);
        $companyNumber = $allCompanies[array_rand($allCompanies)]["number"];
        $responseTime = microtime(true) - $startTime;
        echo round($responseTime, 4) . " seconds to find a random company<br>";
        return self::getCompanyFromDatabase($companyNumber);
    }
}