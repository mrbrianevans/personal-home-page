<a href="crawler" class="darker">Crawl</a>
<form method="get" action="company-search">
    <label for="companySearchBox">Search for a company: </label>
    <input type="text" placeholder="Bitcoin" name="query" id="companySearchBox"/>
    <br>
    <label for="sortByAgeSelection">Sort by company age?</label>
    <select name="sortByAge" id="sortByAgeSelection"><option value="asc">Newest to oldest</option><option value="desc">Oldest to newest</option></select>
</form>
<?php
$request = curl_init("https://api.companieshouse.gov.uk/search/companies?q=car&items_per_page=2");
curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($request, CURLOPT_USERPWD, "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S:");
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($request);
$result = json_decode($result, true);

echo "<h3>Information about each company in a search result</h3>";
echo "<ul>";
foreach($result["items"][0] as $key=>$value){
    echo "<li>";
    echo "$key: -> ";
    var_dump($value);
    echo "</li>";
}
echo "</ul>";

echo "<h3>Information about each search result</h3>";
echo "<ul>";
foreach($result as $key=>$value){
    echo "<li>";
    echo "$key: -> $value";
//    var_dump($value);
    echo "</li>";
}
echo "</ul>";

?>