<?php
if(isset($_POST['uploadButton'])) {
    $session_id = session_id();
    $directory = "fileStorage/" . $session_id;
    $csvUpload = $_FILES["csvUpload"];
    $file_loc = "$directory/upload." . pathinfo($csvUpload["name"], PATHINFO_EXTENSION);
    $uploadOK = 1;
    $fileType = strtolower(pathinfo($file_loc, PATHINFO_EXTENSION));
    if ($fileType != "csv") { // file type of CSV check
        echo "Please upload CSV format";
        echo "<br>Your file type was $fileType";
        $uploadOK = 0;
    }
    if ($csvUpload["size"] > 5000000) { // 5 megabytes should be enough
        echo "Your file is too large. Please try a file smaller than 5mb";
        $uploadOK = 0;
    }
    if ($uploadOK) {
        mkdir($directory);
        //upload file
        if (move_uploaded_file($csvUpload["tmp_name"], $file_loc)) {
            require "tradesHistory.php";
            $tradesHistoryObject = new tradesHistory($session_id);
            if (!count($tradesHistoryObject->errorList)) {
                setcookie("folder", "fileStorage/" . $session_id, time()+86400*365, "/investing"); // only sets the cookie if there were no errors
                $numberOfInstruments = count($tradesHistoryObject->instruments);
                if($numberOfInstruments>5){
                    setcookie("graph", "drawPortfolioValue", time()+86400*30, "/investing");
                }else{
                    setcookie("graph", "drawInstrumentsValue", time()+86400*30, "/investing");
                }
                $beforeCalcs = microtime(true);
                $dailyInstrumentQuantities = $tradesHistoryObject->getDailyInstrumentQuantities();
                $dailyInstrumentsValue = $tradesHistoryObject->getDailyInstrumentValues($dailyInstrumentQuantities);
                tradesHistory::convertToGraphJSON($dailyInstrumentsValue, $session_id, "dailyInstrumentsValues", 2);

                $dailyPortfolioValue = $tradesHistoryObject->getDailyPortfolioValue($dailyInstrumentsValue);
                tradesHistory::convertToGraphJSON($dailyPortfolioValue, $session_id, "dailyPortfolioValue", 2);

                $annualisedRateOfReturn = round($tradesHistoryObject->getAnnualisedRateOfReturnOnPortfolio($dailyPortfolioValue), 4) * 100;
                $bankBalanceHistory = $tradesHistoryObject->getOutOfBankBalance();

                $combinedBalanceHistory = $tradesHistoryObject->getDailyPortfolioValueAndBankBalance($dailyPortfolioValue, $bankBalanceHistory);
                tradesHistory::convertToGraphJSON($combinedBalanceHistory, $session_id, "dailyPortfolioValueAndBankBalance", 2);

                $dailyAnnualisedRateOfReturn = $tradesHistoryObject->getDailyAnnualisedRateOfReturnOnPortfolio($dailyPortfolioValue);
                $dailyRateOfReturn = $tradesHistoryObject->getDailyRateOfReturnOnPortfolio($dailyPortfolioValue);
                $dietzROR = round($tradesHistoryObject->calculateModifiedDietzRateOfReturn($combinedBalanceHistory), 4) * 100;

                $monthlyTimeWeightedReturns = $tradesHistoryObject->getMonthlyTimeWeightedRateOfReturn($dailyPortfolioValue);
                tradesHistory::convertToGraphJSON($monthlyTimeWeightedReturns, $session_id, "monthlyTimeWeightedReturns", 4);
                $calcsTime = microtime(true) - $beforeCalcs;

                $database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
                if (isset($_COOKIE["folder"])) {
                    $sql = "UPDATE investing SET session='duplicate of $session_id' WHERE session='$session_id'";
                    if($database->query($sql)){
                        $sql = "INSERT INTO investing (session, no_of_instruments, days_in_range, price_history_time, cleaning_time, ror_time, calcs_time) 
                                    VALUES  ('$session_id', '$numberOfInstruments', '".$tradesHistoryObject->numberOfDays."', '".$tradesHistoryObject->timer["priceHistoryTime"]."', '".
                            $tradesHistoryObject->timer["cleaningTime"]."', '".$tradesHistoryObject->timer["ror_time"]."', '$calcsTime')";
                        if($database->query($sql))
                            $database->close();
                        else{
                            echo "<br>Could not save statistics about portfolio because: ".$database->error."<br>";
                            mail("brian@brianevans.tech", "Investing SQL failure", "Hi Brian\n
                    The SQL query on the investment management site has failed due to:\n
                    ".$database->error."\n
                    Please could you look into it\n\n
                    Thanks, \n The Mailbot");
                        }
                    }
                }else{
                    $sql = "INSERT INTO investing (session, no_of_instruments, days_in_range, price_history_time, cleaning_time, ror_time, calcs_time) 
                                    VALUES  ('$session_id', '$numberOfInstruments', '".$tradesHistoryObject->numberOfDays."', '".$tradesHistoryObject->timer["priceHistoryTime"]."', '".
                        $tradesHistoryObject->timer["cleaningTime"]."', '".$tradesHistoryObject->timer["ror_time"]."', '$calcsTime')";
                    if($database->query($sql))
                        $database->close();
                    else{
                        echo "<br>Could not save statistics about portfolio because: ".$database->error."<br>";
                        mail("brian@brianevans.tech", "Investing SQL failure", "Hi Brian\n
                    The SQL query on the investment management site has failed due to:\n
                    ".$database->error."\n
                    Please could you look into it\n\n
                    Thanks, \n The Mailbot");
                    }
                }
                $numberOfWarnings = count($tradesHistoryObject->warningList);
                if($numberOfWarnings){
                    echo "<br>There are $numberOfWarnings warnings:<br>";
                    foreach($tradesHistoryObject->warningList as $warning){
                        echo "<div class='warningMessage'>";
                        echo "<b>" . $warning["WarningName"] . ":</b> (" . $warning["count"] . ")  <br> " . $warning["WarningDesc"];
                        echo "</div>";
                    }
                }


                $portfolioStats = array(
                    "no_of_instruments"=>$numberOfInstruments,
                    "days_in_range"=>$tradesHistoryObject->numberOfDays,
                    "calcs_time"=>$calcsTime);
                require "graphView.php";
            }
            else{
                $number_of_errors = count($tradesHistoryObject->errorList);
                echo "<br>There were $number_of_errors errors reading the CSV:<br>";
                foreach($tradesHistoryObject->errorList as $error){
                    echo "<div class='errorMessage'>";
                    echo "<b>Error:</b> " . $error["ErrName"] . ". <br><b>Description:</b> " . $error["ErrDesc"];
                    echo "</div>";
                }
                echo "<a href=\"index.php?newupload=true\"><button id=\"newUploadButton\">Upload a new file</button></a>";
            }
        } else {
            echo "Upload to $file_loc failed, please try again";
        }
    }
}
elseif (isset($_GET["created"])) {
    $session_id = session_id();
    copy("fileStorage/$session_id/created.csv", "fileStorage/$session_id/upload.csv");

    require "tradesHistory.php";
    $tradesHistoryObject = new tradesHistory($session_id);
    if (!count($tradesHistoryObject->errorList)) {
        setcookie("folder", "fileStorage/" . $session_id, time()+86400*365, "/investing"); // only sets the cookie if there were no errors
        $numberOfInstruments = count($tradesHistoryObject->instruments);
        if($numberOfInstruments>5){
            setcookie("graph", "drawTimeWeightedRateOfReturn", time()+86400*30, "/investing");
        }else{
            setcookie("graph", "drawInstrumentsValue", time()+86400*30, "/investing");
        }
        $beforeCalcs = microtime(true);
        $dailyInstrumentQuantities = $tradesHistoryObject->getDailyInstrumentQuantities();
        $dailyInstrumentsValue = $tradesHistoryObject->getDailyInstrumentValues($dailyInstrumentQuantities);
        tradesHistory::convertToGraphJSON($dailyInstrumentsValue, $session_id, "dailyInstrumentsValues", 2);

        $dailyPortfolioValue = $tradesHistoryObject->getDailyPortfolioValue($dailyInstrumentsValue);
        tradesHistory::convertToGraphJSON($dailyPortfolioValue, $session_id, "dailyPortfolioValue", 2);

        $annualisedRateOfReturn = round($tradesHistoryObject->getAnnualisedRateOfReturnOnPortfolio($dailyPortfolioValue), 4) * 100;
        $bankBalanceHistory = $tradesHistoryObject->getOutOfBankBalance();

        $combinedBalanceHistory = $tradesHistoryObject->getDailyPortfolioValueAndBankBalance($dailyPortfolioValue, $bankBalanceHistory);
        tradesHistory::convertToGraphJSON($combinedBalanceHistory, $session_id, "dailyPortfolioValueAndBankBalance", 2);

        $dailyAnnualisedRateOfReturn = $tradesHistoryObject->getDailyAnnualisedRateOfReturnOnPortfolio($dailyPortfolioValue);
        $dailyRateOfReturn = $tradesHistoryObject->getDailyRateOfReturnOnPortfolio($dailyPortfolioValue);
        $dietzROR = round($tradesHistoryObject->calculateModifiedDietzRateOfReturn($combinedBalanceHistory), 4) * 100;

        $monthlyTimeWeightedReturns = $tradesHistoryObject->getMonthlyTimeWeightedRateOfReturn($dailyPortfolioValue);
        tradesHistory::convertToGraphJSON($monthlyTimeWeightedReturns, $session_id, "monthlyTimeWeightedReturns", 4);
        $calcsTime = microtime(true) - $beforeCalcs;

        $database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if (isset($_COOKIE["folder"])) {
            $sql = "UPDATE investing SET session='duplicate of $session_id' WHERE session='$session_id'";
            if($database->query($sql)){
                $sql = "INSERT INTO investing (session, no_of_instruments, days_in_range, price_history_time, cleaning_time, ror_time, calcs_time) 
                                    VALUES  ('$session_id', '$numberOfInstruments', '".$tradesHistoryObject->numberOfDays."', '".$tradesHistoryObject->timer["priceHistoryTime"]."', '".
                    $tradesHistoryObject->timer["cleaningTime"]."', '".$tradesHistoryObject->timer["ror_time"]."', '$calcsTime')";
                if($database->query($sql))
                    $database->close();
                else{
                    echo "<br>Could not save statistics about portfolio because: ".$database->error."<br>";
                    mail("brian@brianevans.tech", "Investing SQL failure", "Hi Brian\n
                    The SQL query on the investment management site has failed due to:\n
                    ".$database->error."\n
                    Please could you look into it\n\n
                    Thanks, \n The Mailbot");
                }
            }
        }else{
            $sql = "INSERT INTO investing (session, no_of_instruments, days_in_range, price_history_time, cleaning_time, ror_time, calcs_time) 
                                    VALUES  ('$session_id', '$numberOfInstruments', '".$tradesHistoryObject->numberOfDays."', '".$tradesHistoryObject->timer["priceHistoryTime"]."', '".
                $tradesHistoryObject->timer["cleaningTime"]."', '".$tradesHistoryObject->timer["ror_time"]."', '$calcsTime')";
            if($database->query($sql))
                $database->close();
            else{
                echo "<br>Could not save statistics about portfolio because: ".$database->error."<br>";
                mail("brian@brianevans.tech", "Investing SQL failure", "Hi Brian\n
                    The SQL query on the investment management site has failed due to:\n
                    ".$database->error."\n
                    Please could you look into it\n\n
                    Thanks, \n The Mailbot");
            }
        }
        $numberOfWarnings = count($tradesHistoryObject->warningList);
        if($numberOfWarnings){
            echo "<br>There are $numberOfWarnings warnings:<br>";
            foreach($tradesHistoryObject->warningList as $warning){
                echo "<div class='warningMessage'>";
                echo "<b>" . $warning["WarningName"] . ":</b> (" . $warning["count"] . ")  <br> " . $warning["WarningDesc"];
                echo "</div>";
            }
        }


        $portfolioStats = array(
            "no_of_instruments"=>$numberOfInstruments,
            "days_in_range"=>$tradesHistoryObject->numberOfDays,
            "calcs_time"=>$calcsTime);
        require "graphView.php";
    }
    else{
        $number_of_errors = count($tradesHistoryObject->errorList);
        echo "<br>There were $number_of_errors errors reading the CSV:<br>";
        foreach($tradesHistoryObject->errorList as $error){
            echo "<div class='errorMessage'>";
            echo "<b>Error:</b> " . $error["ErrName"] . ". <br><b>Description:</b> " . $error["ErrDesc"];
            echo "</div>";
        }
        echo "<a href=\"index.php?newupload=true\"><button id=\"newUploadButton\">Upload a new file</button></a>";
    }

}
elseif(isset($_GET["newupload"])){
    require "uploadView.php";
}
elseif (isset($_GET["samplePortfolio"])) {
    setcookie("folder", "fileStorage/sample");
    setcookie("graph", "drawTimeWeightedRateOfReturn");
    header("Location: index.php");
}
elseif(isset($_COOKIE["folder"])){
    $database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $session_id = str_replace("fileStorage/", "", $_COOKIE["folder"]);
    $sql = "SELECT no_of_instruments, days_in_range, calcs_time FROM investing WHERE session='$session_id' LIMIT 1";
    $portfolioStats = $database->query($sql)->fetch_assoc();
    $database->close();
    require "graphView.php";
}
else {
    require "uploadView.php";
}