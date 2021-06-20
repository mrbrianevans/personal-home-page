<?php


class tradesHistory
{
    private $session;
    private $transactionArray;
    public $errorList;
    private $columns;
    public $instruments;
    private $minDate;
    private $maxDate;
    public $numberOfDays;
    private $relevantPriceHistory;
    public $timer;
    public $warningList;


    public function __construct($session){
        $startTime = microtime(true);
        $this->session = $session;
        $this->checkAndCleanData();
        $this->timer["cleaningTime"] = microtime(true) - $startTime;
        if(!count($this->errorList)){
            $this->setMinMaxDate();
            $this->setInstrumentsList();

            $startTime = microtime(true);
            $this->setRelevantPriceHistory();
            $this->timer["priceHistoryTime"] = microtime(true) - $startTime;
        }
    }
    public function __destruct()
    {
        unset($this->transactionArray);
    }

    private function addWarning($name, $message){
        $alreadyRegistered = false;
        foreach($this->warningList as &$warning){
            if($warning["WarningDesc"]===$message){
                $warning["count"]++;
                $alreadyRegistered = true;
            }
        }
        unset($warning);
        if(!$alreadyRegistered){
            $this->warningList[] = array(
                "WarningName"=>$name,
                "WarningDesc"=>$message,
                "count"=>1
            );
        }
    }

    private function checkAndCleanData(){
        $fileLocation = "fileStorage/".$this->session."/upload.csv";
        $unsupportedTickers = [];
        if(file_exists($fileLocation))
            $transactionArray = array_map('str_getcsv', file($fileLocation));
        else
            $this->errorList[] = array(
                "ErrNo"=>11,
                "ErrName"=>"Cannot find CSV upload",
                "ErrDesc"=>"The CSV upload is missing from file storage"
            );
        // {[0]=>{"ErrNo"=>"5", "ErrName"=>"Unreadable date format", "ErrDesc"=>"31.5.2020 should be in dd.mm.yyyy format"}}

        //check
        //TODO: check column headers
        $columns = array_map("trim", $transactionArray[0]); // get rid of whitespace on ends of column names
        $correctFormat = array("instrument", "quantity", "direction", "datetime", "price");
        $missing_columns = array_diff($correctFormat, $columns);
        $additional_columns = array_diff($columns, $correctFormat);

        foreach($missing_columns as $missing_column){
            $this->errorList[] = array(
                "ErrNo"=>1,
                "ErrName"=>"Missing column in CSV",
                "ErrDesc"=>"The CSV upload is missing the $missing_column column"
            );
        }
        unset($missing_column);
        foreach($additional_columns as $additional_column){
            $this->errorList[] = array(
                "ErrNo"=>2,
                "ErrName"=>"Additional column in CSV",
                "ErrDesc"=>"The CSV upload has an additional column called $additional_column"
            );
        }
        unset($additional_column);
        if(!count($missing_columns)){// after column headers have been checked and are present
            $this->transactionArray = $transactionArray; // only set it if the columns are correct
            $this->setColumnOrder(); // this is necessary for the other checks/cleans
            //checks every row to match the correct format for each value
            for($i = 0; $i < count($this->transactionArray); $i++){
                $dateValue = $this->transactionArray[$i][$this->columns["datetime"]];
                $directionValue = trim(strtolower($this->transactionArray[$i][$this->columns["direction"]]));
                $priceValue = trim(str_replace("$", "", $this->transactionArray[$i][$this->columns["price"]]));
                $priceValue = str_replace(",", "", $priceValue);
                $quantityValue = trim($this->transactionArray[$i][$this->columns["quantity"]]);
                $tickerValue = str_replace(".", "", trim(strtoupper($this->transactionArray[$i][$this->columns["instrument"]])));

                if(!strtotime($dateValue)){ // date format
                    $this->errorList[] = array(
                        "ErrNo"=>3,
                        "ErrName"=>"Unreadable datetime format",
                        "ErrDesc"=>"The date format $dateValue cannot be read by the server. Please try dd.mm.yyyy or mm/dd/yyyy"
                    );
                }

                // direction BUY or SELL
                $this->transactionArray[$i][$this->columns["direction"]] = $directionValue; // update to lowercase
                if (!(($directionValue == "buy") or ($directionValue == "sell"))) {
                    $this->errorList[] = array(
                        "ErrNo"=>4,
                        "ErrName"=>"Unreadable direction",
                        "ErrDesc"=>"The direction '$directionValue' cannot be read by the server. Only 'buy' and 'sell' are acceptable"
                    );
                }

                //price check
                $this->transactionArray[$i][$this->columns["price"]] = $priceValue;
                if (!is_numeric($priceValue)) {
                    $this->errorList[] = array(
                        "ErrNo"=>5,
                        "ErrName"=>"Unreadable price",
                        "ErrDesc"=>"The price $priceValue cannot be read by the server. Only numbers are acceptable"
                    );
                }elseif ($quantityValue < 0) {
                    $this->errorList[] = array(
                        "ErrNo"=>8,
                        "ErrName"=>"Negative instrument price",
                        "ErrDesc"=>"The price \$$quantityValue is negative"
                    );
                }

                //quantity check
                $this->transactionArray[$i][$this->columns["quantity"]] = $quantityValue;
                if (!is_numeric($quantityValue)) {
                    $this->errorList[] = array(
                        "ErrNo"=>6,
                        "ErrName"=>"Unreadable quantity",
                        "ErrDesc"=>"The quantity $quantityValue cannot be read by the server. Only numbers are acceptable"
                    );
                }elseif ($quantityValue < 0) {
                    $this->errorList[] = array(
                        "ErrNo"=>9,
                        "ErrName"=>"Negative quantity",
                        "ErrDesc"=>"The quantity $quantityValue is negative"
                    );
                }

                //ticker check
                $this->transactionArray[$i][$this->columns["instrument"]] = $tickerValue;
                if (!file_exists("price_history/$tickerValue.json") and is_null($unsupportedTickers[$tickerValue])) {
                    $unsupportedTickers[$tickerValue] = true;
                    // tries to download ticker info if it doesn't already exist
                    require_once "investingModel.php";
                    investingModel::formatHistoricalPriceData("$tickerValue");
                    // check if its there now, if not, throw an error
                    if (!file_exists("price_history/$tickerValue.json")) {
                        $this->errorList[] = array(
                            "ErrNo" => 7,
                            "ErrName" => "Ticker not found",
                            "ErrDesc" => "The price history for $tickerValue(row $i) cannot be found by the server. Only instruments listed on Yahoo Finance are supported"
                        );
                        // keeps a counter of how many times each ticker has been requested. if its more than 50 I can look into that one specifically
                        $requestedTickers = json_decode(file_get_contents("requestedTickers.json"));
                        $requestedTickers->$tickerValue++;
                        file_put_contents("requestedTickers.json", json_encode($requestedTickers));
                    }
                }
            }
            unset($i);
            unset($dateValue);
            unset($priceValue);
            unset($quantityValue);
            unset($tickerValue);
            unset($directionValue);

            //TODO: check buy/sell in direction column [--DONE--]
            //TODO: assert price and quantity are always positive [--DONE--]
            //TODO: check that I have all the required price history files for the tickers present [--DONE--]
            //TODO: check that price was within 10% of the market price for that day

            //clean
            //TODO: Make all tickers UPPER CASE and buy/sell lower case [--DONE--]
            //TODO: Trim all whitespace [--DONE--]
            //TODO: remove dollar signs A$AP [--DONE--]
            //TODO: sort by date - I'm not sure if this is necessary
        }
        //this then gives peace of mind for all future calculations

    }

    private function setColumnOrder(){
        $column_row = array_shift($this->transactionArray);
        $this->columns["instrument"] = array_search("instrument", $column_row);
        $this->columns["datetime"] = array_search("datetime", $column_row);
        $this->columns["direction"] = array_search("direction", $column_row);
        $this->columns["quantity"] = array_search("quantity", $column_row);
        $this->columns["price"] = array_search("price", $column_row);
    }

    private function setMinMaxDate(){
        $this->minDate = time();
        $this->maxDate = 0;
        foreach($this->transactionArray as $transaction){
            $instruments[$transaction[$this->columns["instrument"]]] = 0; //gets list of instruments in associative array : {"instrument"=>0}

            $transaction_timestamp = strtotime($transaction[$this->columns["datetime"]]);
            if(!$transaction_timestamp)
                echo "Date ".$transaction[$this->columns["datetime"]]." in unreadable format"; // string to time failed
            else{
                if($transaction_timestamp < $this->minDate)
                    $this->minDate = $transaction_timestamp;
                if ($transaction_timestamp > $this->maxDate)
                    $this->maxDate = $transaction_timestamp;
            }
        }
        unset($row);
        unset($transaction_timestamp);
        $this->maxDate = time()-87000;
        $this->numberOfDays = ($this->maxDate - $this->minDate) / 86400;
    }

    private function setInstrumentsList(){
        //gets list of instruments in associative array : {"instrument"=>0}
        foreach($this->transactionArray as $transaction) {
            $this->instruments[$transaction[$this->columns["instrument"]]] = 0;
        }
    }

    private function setRelevantPriceHistory(){
        foreach ($this->instruments as $instrument=>$zero){
            $historical_data_file = json_decode(file_get_contents("price_history/$instrument.json"));
            for($daily_timestamp = $this->minDate; $daily_timestamp < $this->maxDate+86400; $daily_timestamp+=86400){
                $formatted_date = date("j M Y", $daily_timestamp);
                $this->relevantPriceHistory[$formatted_date][$instrument] = $historical_data_file->$formatted_date;
            }
        }
    }

    /**
     * @return array of bank account balances for each relevant date
     */
    public function getOutOfBankBalance(){
        $bankAccount = [];

        foreach($this->transactionArray as $transaction){
            $transaction_timestamp = strtotime($transaction[$this->columns["datetime"]]);
            $bankAccountChange = ($transaction[$this->columns["quantity"]]*$transaction[$this->columns["price"]]);
            if($transaction[$this->columns["direction"]]=="sell") $bankAccountChange *= (1);
            elseif($transaction[$this->columns["direction"]]=="buy") $bankAccountChange *= (-1);
            else echo "Not a buy or sell?";
            for($daily_timestamp = $transaction_timestamp; $daily_timestamp < $this->maxDate+86400; $daily_timestamp+=86400){
                $formatted_date = date("j M Y", $daily_timestamp);
                $bankAccount[$formatted_date]["bankBalance"] += $bankAccountChange;
            }
        }
        unset($transaction);
        unset($transaction_timestamp);
        unset($bankAccountChange);
//        for($daily_timestamp = $this->minDate; $daily_timestamp < $this->maxDate+86400; $daily_timestamp+=7*86400){
//            $formatted_date = date("j M Y", $daily_timestamp);
//            echo "<br>On $formatted_date, the out of bank cost was $" . $bankAccount[$formatted_date];
//        }
        return $bankAccount;
    }

    /**
     * @return array of daily quantities held of each instrument
     */
    public function getDailyInstrumentQuantities(){
        $holdings = [];

        foreach($this->transactionArray as $transaction){
            $transaction_timestamp = strtotime($transaction[$this->columns["datetime"]]);
            $quantityChange = ($transaction[$this->columns["quantity"]]);
            $direction = $transaction[$this->columns["direction"]];
            if($direction =="sell") $quantityChange *= (-1);
            elseif($direction =="buy") $quantityChange *= (1);
            else{
                $this->errorList[] = array(
                    "ErrNo"=>4,
                    "ErrName"=>"Unreadable direction",
                    "ErrDesc"=>"The direction '$direction' cannot be read by the server. Only 'buy' and 'sell' are acceptable"
                );
            }
            for($daily_timestamp = $transaction_timestamp; $daily_timestamp < $this->maxDate+86400; $daily_timestamp+=86400){
                $formatted_date = date("j M Y", $daily_timestamp);
                $instrument = $transaction[$this->columns["instrument"]];
                $holdings[$formatted_date][$instrument] += $quantityChange;
                if($holdings[$formatted_date][$instrument] < 0){
                    $this->errorList[] = array(
                        "ErrNo"=>10,
                        "ErrName"=>"Negative quantity held",
                        "ErrDesc"=>"On $formatted_date, you had a negative quantity of $instrument"
                    );
                }
            }
        }
        unset($transaction);
        unset($transaction_timestamp);
        unset($quantityChange);
//        for($daily_timestamp = $this->minDate; $daily_timestamp < $this->maxDate+86400; $daily_timestamp+=7*86400){
//            $formatted_date = date("j M Y", $daily_timestamp);
//            echo "<br>On $formatted_date, your holdings were ";
//            print_r($holdings[$formatted_date]);
//        }
        return $holdings;
    }

    /**
     * @param $dailyHoldings array from getDailyInstrumentQuantities()
     * @return array in the format: {"j M Y"=>{"TICKER"=>value}} of the dollar value of each instrument held
     */
    public function getDailyInstrumentValues($dailyHoldings){
        $holdingsValue = []; // {"22 May 2020"=>{"AAPL"=>50, "GOOG"=>100}, "25 May 2020"=>{"AAPL"=>80, "GOOG"=>20}}
        foreach ($this->instruments as $instrument=>$zero) {
            for($daily_timestamp = $this->minDate; $daily_timestamp < $this->maxDate+86400; $daily_timestamp+=86400){
                $formatted_date = date("j M Y", $daily_timestamp);
                $quantity_held = $dailyHoldings[$formatted_date][$instrument];
                $close_price = $this->relevantPriceHistory[$formatted_date][$instrument];
//                echo "<br>$instrument was worth " . $quantity_held * $close_price . " on $formatted_date due to price=$close_price and qty=$quantity_held";
                if($close_price) $holdingsValue[$formatted_date][$instrument] = $quantity_held * $close_price;
                if($holdingsValue[$formatted_date][$instrument] < 0){
                    $this->errorList[] = array(
                        "ErrNo"=>11,
                        "ErrName"=>"Negative value of instrument held",
                        "ErrDesc"=>"On $formatted_date, instrument $instrument had a negative value"
                    );
                }
            }
        }

//        foreach ($holdingsValue as $day=>$instruments){
//            echo "<br>The value of your holdings on $day were ";
//            print_r($instruments);
//        }

        return $holdingsValue;
    }

    public function getDailyPortfolioValue($dailyValue){
        $portfolioValue = [];
        foreach ($dailyValue as $day=>$arrayOfStocks){
            $portfolioValue[$day]["portfolioValue"] = array_sum($dailyValue[$day]);
        }
        return $portfolioValue;
//        return array_map("array_sum", $dailyValue);
    }

    public function getDailyPortfolioValueAndBankBalance($portfolioValue, $bankBalance){
        $totalBalance = [];
        foreach ($portfolioValue as $date=>$value){
            $totalBalance[$date]["portfolioValue"] = $portfolioValue[$date]["portfolioValue"];
            $totalBalance[$date]["bankBalance"] = $bankBalance[$date]["bankBalance"];
        }
        return $totalBalance;
    }

    public function getAnnualisedRateOfReturnOnPortfolio($dailyPortfolioValue){
        $initialValue = current($dailyPortfolioValue)["portfolioValue"];
//        echo "Initial value: $initialValue on ".array_keys($dailyPortfolioValue)[0]."<br>";
        end($dailyPortfolioValue); // get final value of portfolio
        $finalValue = prev($dailyPortfolioValue)["portfolioValue"];
//        echo "Final value: $finalValue on ".array_keys($dailyPortfolioValue)[count($dailyPortfolioValue)-2]."<br>";
        $totalIncrease = $finalValue - $initialValue;
//        echo "Total increase: $totalIncrease<br>";
        $dividends = 0;
        $totalGain = $totalIncrease + $dividends;
        $i = $totalGain/$initialValue;
        $n = (strtotime(array_keys($dailyPortfolioValue)[count($dailyPortfolioValue)-2])-
                strtotime(array_keys($dailyPortfolioValue)[0]))
            /(365*86400);// divided by one year
//        echo "Final calc: (1+".round($i, 3).")<sup>".round($n, 3)."</sup> - 1<br>";
        $annualisedReturn = (1+$i)**(1/$n) - 1; // formula
        return $annualisedReturn;
    }

    public function getDailyAnnualisedRateOfReturnOnPortfolio($dailyPortfolioValue){
        $initialValue = current($dailyPortfolioValue)["portfolioValue"];
        $initialDate = array_keys($dailyPortfolioValue)[0];
        foreach($dailyPortfolioValue as $date=>$valueArray){
            $finalValue = $valueArray["portfolioValue"];
            $totalIncrease = $finalValue - $initialValue;
            $i = $totalIncrease/$initialValue;
            $n = (strtotime($date)-
                    strtotime($initialDate))
                /(365*86400);// divided by one year
            $annualisedReturns[$date]["Annualised Return"] = ((1+$i)**(1/$n) - 1); // formula
        }
        return $annualisedReturns;
    }

    public function getDailyRateOfReturnOnPortfolio($dailyPortfolioValue){
        $initialValue = current($dailyPortfolioValue)["portfolioValue"];
        foreach($dailyPortfolioValue as $date=>$valueArray){
            $finalValue = $valueArray["portfolioValue"];
            $totalIncrease = $finalValue - $initialValue;
            $i = $totalIncrease/$initialValue;
            $annualisedReturns[$date]["Return on Investment"] = $i; // formula not adjusted for time period
        }
        return $annualisedReturns;
    }

    public function getMonthlyRateOfReturn($dailyPortfolioValue){

    }

    public function calculateModifiedDietzRateOfReturn($dailyPortfolioValue){
        //this should be monthly
        $CD = (strtotime($this->maxDate) - strtotime($this->minDate))/86400;
        $v0 = current($dailyPortfolioValue)["portfolioValue"];
        $v1 =end($dailyPortfolioValue)["portfolioValue"];
        $cashFlows = [];
        $weightedCashFlows = [];
        foreach($this->transactionArray as $transaction){
            $Di = (strtotime($transaction[$this->columns["datetime"]]) - strtotime($this->minDate))/86400;
            $wi = ($CD-$Di)/$CD;
            $CFi = $transaction[$this->columns["price"]]*$transaction[$this->columns["quantity"]];
            if($transaction[$this->columns["direction"]]==="sell") $CFi*=(-1);
            $weightedCashFlows[] = $wi * $CFi;
            $cashFlows[] = $CFi;
        }
        $CFiXwi = array_sum($weightedCashFlows);
        $CF = array_sum($cashFlows);
        $dietzRateOfReturn = ($v1 - $v0 - $CF)/($v0+$CFiXwi);
        return $dietzRateOfReturn;
    }

    public function getMonthlyModifiedDietzRateOfReturn($dailyPortfolioValue){

    }

    public function getMonthlyTimeWeightedRateOfReturn($dailyPortfolioValue){
        $startTime = microtime(true);
        $listOfSubPeriods = [];
        $prevMonthAndYear = date("M Y", $this->minDate);
        $monthMapper = []; // [May 2020] => "1 May 2020"
        for($daily_timestamp = $this->minDate; $daily_timestamp < $this->maxDate+86400; $daily_timestamp+=86400){
            $monthAndYear = date("M Y", $daily_timestamp);
            $dayMonthAndYear = date("j M Y", $daily_timestamp);
            if($dailyPortfolioValue[$dayMonthAndYear]["portfolioValue"]){
                if($monthAndYear !== $prevMonthAndYear and !isset($listOfSubPeriods[$dayMonthAndYear])){
                    $listOfSubPeriods[] = $daily_timestamp;
                    $monthMapper[$monthAndYear] = $dayMonthAndYear;
                }
                $prevMonthAndYear = $monthAndYear;
            }
        }
        $monthlyReturns[date("j M Y", $this->minDate)]["Time Weighted Rate of Return"] = 1;
        $monthlyReturns += array_fill_keys(
            array_map("date",
                array_fill(0, count($listOfSubPeriods), "j M Y"), $listOfSubPeriods), array("Time Weighted Rate of Return"=>1));
        $monthMapper[date("M Y", $this->minDate)] = date("j M Y", $this->minDate);
        $listOfMonths = array_map("strtotime", array_keys($monthlyReturns));
        sort($listOfMonths);
        $listOfMonths = array_map("date", array_fill(0, count($listOfMonths), "j M Y"), $listOfMonths); // [0]=>"1 Jan 2020", [1]=>"1 Feb 2020" etc
        $listOfMonths[] = $this->maxDate;
        foreach ($this->transactionArray as $transaction){
            $dateOfTransaction = strtotime(date("j M Y", strtotime($transaction[$this->columns["datetime"]])));
            $shiftedDate = $dateOfTransaction;
            $count = 0;
            while(!$dailyPortfolioValue[date("j M Y", $shiftedDate)]["portfolioValue"]){
                $shiftedDate += 86400;
                if($count++>10){
                    break;
                }
            }
            if($count < 10)
                $listOfSubPeriods[] = $shiftedDate;
        }
        $listOfSubPeriods[] = $this->maxDate;
        $listOfSubPeriods = array_unique($listOfSubPeriods);
        sort($listOfSubPeriods);
        $listOfSubPeriods = array_map("date", array_fill(0, count($listOfSubPeriods), "j M Y"), $listOfSubPeriods);

        for ($period = 0; $period < count($listOfSubPeriods)-1; $period++){
            $startDate = $listOfSubPeriods[$period];
            $endDate = strtotime($listOfSubPeriods[$period+1]);
            do{
                $endDate -= 86400;
            }while(!$dailyPortfolioValue[date("j M Y", $endDate)]["portfolioValue"]);
            if($endDate <= strtotime($startDate)){
                $this->addWarning(
                    "Too many transactions",
                    "Transactions occurred too close together in the month of " . date("F Y", strtotime($startDate)) .
                    " to find an accurate time weighted rate of return. Parts of this graph may be inaccurate");

            }
            $endDate = date("j M Y", $endDate);
            $portfolioValueAtStart = $dailyPortfolioValue[$startDate]["portfolioValue"];
            $portfolioValueAtEnd = $dailyPortfolioValue[$endDate]["portfolioValue"];
            $percentageIncrease = 1+($portfolioValueAtEnd - $portfolioValueAtStart) / $portfolioValueAtStart;
//            echo "<br>Percentage increase from $startDate to $endDate was $percentageIncrease";

            // apply geometric progression
            $month = date("M Y", strtotime($startDate));
            $monthlyReturns[$monthMapper[$month]]["Time Weighted Rate of Return"] *= $percentageIncrease;
        }
        unset($month);
        unset($period);
        foreach(array_keys($monthlyReturns) as $monthAndYear) {
            $monthlyReturn = $monthlyReturns[$monthAndYear]["Time Weighted Rate of Return"];
            $monthlyReturn -= 1;
            $monthlyReturns[$monthAndYear]["Time Weighted Rate of Return"] = $monthlyReturn;
        }
        unset($monthAndYear);
        $this->timer["ror_time"] = microtime(true) - $startTime;
        return $monthlyReturns;
    }
//TODO: Make a Time-weighted one for annual returns rather than monthly
    public function getAnnualTimeWeightedRateOfReturn($dailyPortfolioValue){
        $startTime = microtime(true);
        $listOfSubPeriods = [];
        $prevYear = date("Y", $this->minDate);
        $monthMapper = []; // [May 2020] => "1 May 2020"
        for($daily_timestamp = $this->minDate; $daily_timestamp < $this->maxDate+86400; $daily_timestamp+=86400){
            $year = date("Y", $daily_timestamp);
            $dayMonthAndYear = date("j M Y", $daily_timestamp);
            if($dailyPortfolioValue[$dayMonthAndYear]["portfolioValue"]){
                if($year !== $prevYear and !isset($listOfSubPeriods[$dayMonthAndYear])){
                    $listOfSubPeriods[] = $daily_timestamp;
                    $monthMapper[$year] = $dayMonthAndYear;
                }
                $prevYear = $year;
            }
        }
        $monthlyReturns[date("j M Y", $this->minDate)]["Time Weighted Rate of Return"] = 1;
        $monthlyReturns += array_fill_keys(
            array_map("date",
                array_fill(0, count($listOfSubPeriods), "j M Y"), $listOfSubPeriods), array("Time Weighted Rate of Return"=>1));
        $monthMapper[date("M Y", $this->minDate)] = date("j M Y", $this->minDate);
        $listOfMonths = array_map("strtotime", array_keys($monthlyReturns));
        sort($listOfMonths);
        $listOfMonths = array_map("date", array_fill(0, count($listOfMonths), "j M Y"), $listOfMonths); // [0]=>"1 Jan 2020", [1]=>"1 Feb 2020" etc
        $listOfMonths[] = $this->maxDate;
        foreach ($this->transactionArray as $transaction){
            $dateOfTransaction = strtotime(date("j M Y", strtotime($transaction[$this->columns["datetime"]])));
            $shiftedDate = $dateOfTransaction;
            $count = 0;
            while(!$dailyPortfolioValue[date("j M Y", $shiftedDate)]["portfolioValue"]){
                $shiftedDate += 86400;
                if($count++>10){
                    break;
                }
            }
            if($count < 10)
                $listOfSubPeriods[] = $shiftedDate;
        }
        $listOfSubPeriods[] = $this->maxDate;
        $listOfSubPeriods = array_unique($listOfSubPeriods);
        sort($listOfSubPeriods);
        $listOfSubPeriods = array_map("date", array_fill(0, count($listOfSubPeriods), "j M Y"), $listOfSubPeriods);

        for ($period = 0; $period < count($listOfSubPeriods)-1; $period++){
            $startDate = $listOfSubPeriods[$period];
            $endDate = strtotime($listOfSubPeriods[$period+1])-86400; // this needs to be the day before which isn't great
            do{
                $endDate -= 86400;
            }while(!$dailyPortfolioValue[date("j M Y", $endDate)]["portfolioValue"]);
            $endDate = date("j M Y", $endDate);
            $portfolioValueAtStart = $dailyPortfolioValue[$startDate]["portfolioValue"];
            $portfolioValueAtEnd = $dailyPortfolioValue[$endDate]["portfolioValue"];
            $percentageIncrease = 1+($portfolioValueAtEnd - $portfolioValueAtStart) / $portfolioValueAtStart;
//            echo "<br>Percentage increase from $startDate to $endDate was $percentageIncrease";

            // apply geometric progression
            $month = date("M Y", strtotime($startDate));
            $monthlyReturns[$monthMapper[$month]]["Time Weighted Rate of Return"] *= $percentageIncrease;
        }
        unset($month);
        unset($period);
        foreach(array_keys($monthlyReturns) as $year) {
            $monthlyReturn = $monthlyReturns[$year]["Time Weighted Rate of Return"];
            $monthlyReturn -= 1;
            $monthlyReturns[$year]["Time Weighted Rate of Return"] = $monthlyReturn;
            //TODO: Annualise monthly returns (i actually don't know that I want to do this)
        }
        unset($year);
        $this->timer["ror_time"] = microtime(true) - $startTime;
        return $monthlyReturns;
    }
//TODO: Make variable period time-weighted graphs (ULTIMATE GOAL)

    public static function convertToGraphJSON($array, $session, $name, $dps){
        $rounded = [];
        foreach($array as $day=>$arrayData){
            foreach($arrayData as $label=>$number){
                $rounded[$day][$label] = number_format($number, $dps, ".", ""); // this rounds better than round()
            }
        }
        $encoded = json_encode(($rounded));
        $outputFile = fopen("fileStorage/$session/$name.json", "w");
        fwrite($outputFile, $encoded);
        fclose($outputFile);
    }
}