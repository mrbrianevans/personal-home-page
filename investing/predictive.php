<?php
if (isset($_GET["instr"])) {
    //Find instruments to suggest based on the beginning
    $instr = $_GET["instr"];
    $topTickers = file_get_contents("top100 tickers.csv"); // only recommending top 100 share tickers
    preg_match_all("/^$instr.*/mi", $topTickers, $matches);
    echo json_encode(array_map("trim", $matches[0]));
}
elseif (isset($_GET["instrument"])) {
    $instrument = $_GET["instrument"];
    $date = $_GET["date"];
    $historical_data_file = json_decode(file_get_contents("price_history/$instrument.json"));
    $formatted_date = date("j M Y", strtotime($date));
    echo "\$" . round($historical_data_file->$formatted_date, 2);
}