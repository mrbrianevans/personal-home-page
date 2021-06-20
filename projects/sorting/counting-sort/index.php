<script src="https://www.brianevans.tech/projects/sorting/counting-sort/counting_sort.js"></script>
<?php
require_once "CountingSort.php";
$pi = [3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5, 8, 9, 7, 9, 3, 2, 3, 8, 4, 6];
echo "Using PHP to sort";
foreach ($pi as $index=>$value){
    echo "$value, ";
}
echo "]";

echo "\nSorted pi: \n[";
$sort = CountingSort::counting_sort($pi);
foreach ($sort as $index=>$value){
    echo "$value, ";
}
echo "]";

?>

<p id="sortedPI">[3, 1, 4, 1]</p>
<button onclick="test_count_sort()">Sort</button>
