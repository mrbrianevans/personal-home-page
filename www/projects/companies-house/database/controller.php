<?php
$originalRequestTime = microtime(true);
require "companyDatabaseModel.php";
$crawler = new companyDatabaseModel();
    if(isset($_REQUEST["action"])){
        $action = strtolower($_REQUEST["action"]);
        $returnRequestTime = true;
    switch ($action){
        case "search":
            $companyNumber = trim($_GET["number"]);
            $companyDetailsArray = $crawler->getCompanyFromDatabase($companyNumber);
            require "companyDatabaseDashboardView.php";
            break;
        case "random":
            $companyDetailsArray = $crawler->getRandomCompanyFromDatabase();
            require "companyDatabaseDashboardView.php";
            break;
        case "sample":
            $companyDetailsArray = $crawler->getRandomCompanyWithAccounts();
            require "companyDatabaseDashboardView.php";
            break;
        case "sql":
            $crawler->putCsvIntoDatabase();
            break;
        case "accounts":
            $crawler->getDetailsFromAccounts();
            break;
        case "count":
            echo number_format($crawler->getNumberOfDatabaseEntries()) . " companies";
            break;
        case "predict":
            echo $crawler->predictCompanyNumber($_GET["typed"]);
            $returnRequestTime = false;
            break;
        case "filter":
            $companyList = $crawler->getCompanyList($_GET["limit"], $_GET["sort"]);
            require "companyListView.php";
            break;
        case "count-accounts":
            echo number_format($crawler->countCompaniesWithAccounts()) . " companies have accounts in my database";
            break;
        case "xml":
            $crawler->getDetailsFromAccountsXML();
            break;
        case "interpret":
            $crawler->interpretContextFromAccounts();
            break;
        case "screen":
            $companyList = $crawler->sortSearchByFinancial($_GET["label"], $_GET["order"], $_GET["min"], $_GET["max"]);
            require "companyListView.php";
            break;
        case "persons":
            $crawler->scanTextFileForPersonsWithSignificantControl();
            break;
        case "delete-financials":
            $crawler->deleteUnnecessaryFinancials();
            break;
        case "delete-persons":
            $crawler->deletedCeasedPersons();
            break;
        case "person-search":
            $persons = $crawler->searchByPerson($_GET["name"]);
            require "personListView.php";
            break;
        case "persons-companies":
            $personQuery = $crawler->getCompanyListByPerson($_GET["firstName"], $_GET["lastName"], $_GET["birthMonth"], $_GET["birthYear"]);
            $person = $personQuery["person"];
            require "personView.php";
            $companyList = $personQuery["companies"];
            require "companyListView.php";
            break;
        case "sic-options":
            echo $crawler->getSicCodesForSelect();
            $returnRequestTime = false;
            break;
        case "screen-sic":
            $companyList = $crawler->searchCompaniesBySicCode($_GET["sic"]);
            require "companyListView.php";
            break;
        case "screen-psc-age":
            if(is_numeric($_GET["lower-age-bound"])&&is_numeric($_GET["lower-age-bound"])){
                $companyList = $crawler->searchCompaniesByPscAge($_GET["lower-age-bound"], $_GET["upper-age-bound"]);
                require "companyListView.php";
            }
            else echo "Please enter a number as an age bound. You entered ".$_GET["lower-age-bound"]." and ". $_GET["upper-age-bound"];
            break;
        case "bulk-psc-details":
            $companyNumbersTextarea = $_POST["company-numbers"];
            $companyNumbers = explode("\r\n", $companyNumbersTextarea);
            echo '<p>' . count($companyNumbers) . ' companies submitted</p>';
//            echo "Diagnostic: Companies numbers received: ";
//            print_r($companyNumbers);
            $pscDetails = $crawler->getPscDetailsForCompanies($companyNumbers);
            require "pscListView.php";
            break;
        case "random-company-number":
            echo $crawler->getRandomCompanyNumberWithAccounts();
            $returnRequestTime = false;
            break;
        case "random-company-numbers": //plural
            assert(is_numeric($_REQUEST['qty']), 'Quantity must be numeric');
            if(is_numeric($_REQUEST['qty']))
                echo json_encode($crawler->getRandomCompanyNumbers($_REQUEST['qty']));
            $returnRequestTime = false;
            break;
        case "company-api":
            echo $crawler->getCompanyInfoFromDatabase($_REQUEST["number"]);
            $returnRequestTime = false;
            break;
        default:
            echo "<p>Method not found: </p>";
            var_dump($_REQUEST);
            break;
    }
}else {
//        $crawler->getListOfFinancialLabels();
        require "navigator.php";
    }
    $requestProcessingTime = number_format(microtime(true) - $originalRequestTime, 6);
    if($returnRequestTime) echo "<div style='
text-align: right;
    font-size: smaller;
    color: #2F302F;'>Request processing took $requestProcessingTime seconds</div>";