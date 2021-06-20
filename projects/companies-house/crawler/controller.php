<?php
require "crawlingCompaniesModel.php";
$crawler = new companyDatabaseModel();
    if(isset($_GET["action"])){
        $action = strtolower($_GET["action"]);
    switch ($action){
        case "crawl":
            $crawler->runAlphabetCrawl();
            break;
        case "sort":
            echo "<meta http-equiv=\"refresh\" content=\"3\"/>";
            $crawler->sortByCompanyNumber();
            break;
        case "secondary":
//            echo "<meta http-equiv=\"refresh\" content=\"3\"/>";
            $crawler->secondarySort();
            break;
        case "files":
            $crawler->listSortedFiles();
            break;
        case "stored":
            $crawler->countStoredCompanies();
            break;
        case "search":
            $companyNumber = trim($_GET["companyNumber"]);
            $companyDetailsArray = companyDatabaseModel::findCompany($companyNumber);
            if(!$companyDetailsArray) $companyDetailsArray = $crawler->findCompanyUsingAPI($companyNumber);
            require "companyDashboardView.php";
            break;
        case "database":
            $companyNumber = trim($_GET["companyNumber"]);
            $companyDetailsArray = $crawler->getCompanyFromDatabase($companyNumber);
            require "companyDatabaseDashboardView.php";
            break;
        case "randatabase":
            $companyDetailsArray = $crawler->getRandomCompanyFromDatabase();
            require "companyDatabaseDashboardView.php";
            break;
        case "keys":
            $crawler->fixKeysOfSortedFiles();
            break;
        case "fix":
            $crawler->fixKeysInCategory($_GET["category"]);
            break;
        case "query":
            companyDatabaseModel::addQueryToQuery($_GET["query"]);
            header("Location: ?");
            break;
        case "queue":
            $crawler->viewQueryQueue();
            break;
        case "reset":
            $crawler->resetAllCategorisedPages();
            header("Location: ?");
            break;
        case "csv":
            echo "<meta http-equiv=\"refresh\" content=\"3\"/>";
            $crawler->getFreeCompanyDataProduct();
            break;
        case "sql":
            echo "<meta http-equiv=\"refresh\" content=\"2\"/>";
            $crawler->putCsvIntoDatabase();
            break;
        case "unsorted":
            $crawler->getUncategorisedFigures();
            break;
        case "broken":
            $crawler->findBrokenFiles();
            break;
        case "random":
            $randomCompanyNumber = false;
            while(!($randomCompanyNumber)) $randomCompanyNumber = $crawler->generateRandomWeightedCompanyNumber();
            $companyDetailsArray = companyDatabaseModel::findCompany($randomCompanyNumber);
            if($companyDetailsArray)
                require "companyDashboardView.php";
            else
                echo "Company $randomCompanyNumber not found";
            break;
        case "assets":
            $crawler->getNetAssetsFromAccounts();
            break;
        default:
            var_dump($_GET);
            break;
    }
}else
    require "navigator.php";