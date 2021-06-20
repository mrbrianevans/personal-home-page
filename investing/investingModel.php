<?php


class investingModel
{
    /**
     * This is for general actions not relating to a specific transaction history.
     */
    public function __construct()
    {
    }

    /**
     * Downloads the price history for the last ten years of a instrument from Yahoo Finance
     * @param $ticker string to download
     */
    public static function formatHistoricalPriceData($ticker){
        $requestURL = "https://query1.finance.yahoo.com/v7/finance/download/$ticker?period1=".(time()-(10*365*86400))."&period2=".(time()-86400)."&interval=1d&events=history";
        $requstCURL = curl_init($requestURL);
        curl_setopt($requstCURL, 19913, 1); // return the response instead of printing it
        $filePriceHistory = curl_exec($requstCURL);
        $filePriceHistory = explode("\n", $filePriceHistory);
        if(count($filePriceHistory) > 2){
            $arrayPriceHistory = array_map("str_getcsv", $filePriceHistory);
            $expectedColumns = array('Date','Open','High','Low','Close','Adj Close','Volume');
            assert($arrayPriceHistory[0] === $expectedColumns, "Columns don't match expected format for historical price data");
            $instrument_price_history = [];
            foreach ($arrayPriceHistory as $row) {
                if(strtotime($row[0])){
                    $instrument_price_history[date("j M Y", strtotime($row[0]))] = trim($row[4]);
                }
            }
            $newFile = fopen("price_history/$ticker.json", "w");
            fwrite($newFile, json_encode($instrument_price_history));
            fclose($newFile);
        }
    }
}