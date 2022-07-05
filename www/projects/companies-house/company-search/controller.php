<?php
if(isset($_GET["query"])) {
$startTime = microtime(true);
$query = $_GET["query"];
$request = curl_init("https://api.companieshouse.gov.uk/search/companies?q=$query&items_per_page=100");
curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($request, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($request);
curl_close($request);
$result = json_decode($result, true);
$allResultingItems = $result["items"];
$numberOfResults = $result["total_results"];
$numberOfResultsShown = min($result["total_results"] ?? 0, 999); // cap results at 500 per query to save resources
for($i=100;$i<$numberOfResultsShown;$i+=100){
    $request = curl_init("https://api.companieshouse.gov.uk/search/companies?q=$query&items_per_page=100&start_index=$i");
    curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($request, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($request);
    curl_close($request);
    $result = json_decode($result, true);
    $allResultingItems = array_merge($allResultingItems, $result["items"]);
}
$datesGraph = [];
foreach($allResultingItems as $key=>$item){
    if(isset($item["date_of_creation"]))
        $datesGraph[substr($item["date_of_creation"], 0, 4)] ++;
    if($item["company_status"] === "active"){
    $ageOfCompany = strtotime($item["date_of_creation"]);
    $ageOfCompany = time() - $ageOfCompany;
    $ageOfCompany = number_format($ageOfCompany/(60*60*24*365));
    $allResultingItems[$key]["age"] = $ageOfCompany;
    }else{
        unset($allResultingItems[$key]);
    }
}
$firstYear = min(array_keys($datesGraph));
$graphData = [];
for($year=$firstYear-1; $year <= date("Y"); $year++){
    $graphData[] = array("year"=>$year, "Companies started"=>$datesGraph[$year] ?? 0);
}
?>
<script type="text/javascript">
let data = JSON.parse('<?php print_r(json_encode($graphData)); ?>');
let query = "<?=ucwords($query)?>";
</script>
<?php
if(isset($_GET["sortByAge"])){
    if($_GET["sortByAge"]==="asc") $sortOrder = SORT_ASC;
    elseif($_GET["sortByAge"]) $sortOrder = SORT_DESC;
    array_multisort(array_column($allResultingItems, "age"), $sortOrder, $allResultingItems);
}
$timeTaken = number_format(microtime(true) - $startTime, 3);
$itemsCount = count($allResultingItems);
echo "Showing $itemsCount out of $numberOfResults results in $timeTaken seconds";
?>
<div id="companyFoundingGraph"></div>
    <table class='companies-house'>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Date created</th>
            <th>Age (years)</th>
            <th>Region</th>
        </tr>

    <?php
foreach($allResultingItems as $key=>$item){

    ?>
    <tr>
        <td><?=$key+1?></td>
        <td><a class="darker" href="?company_info=<?=$item["company_number"]?>"><?=ucwords($item["title"])?></a></td>
        <td><?=$item["date_of_creation"]?></td>
        <td><?=$item["age"]?></td>
        <td><?=$item["address"]["region"]??$item["address"]["locality"] ?></td>
    </tr>
    <?php
}
echo "</table>";
}elseif (isset($_GET["company_info"])){
    require "companyView.php";
} elseif (isset($_GET["filing_history"])) {
    require "filingHistory.php";
} elseif (isset($_GET["transaction_id"])) {
    require "accounts.php";
}else{
    ?>

<form method="get" action="">
    <label for="companySearchBox">Search for a company: </label>
    <input type="text" placeholder="Bitcoin" name="query" id="companySearchBox"/>
    <br>
    <label for="sortByAgeSelection">Sort by company age?</label>
    <select name="sortByAge" id="sortByAgeSelection"><option value="asc">Newest to oldest</option><option value="desc">Oldest to newest</option></select>
</form>

    <?php
}