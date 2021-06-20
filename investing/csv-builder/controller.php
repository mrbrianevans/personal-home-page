<?php
$session_id = session_id();
mkdir("../fileStorage/$session_id");
file_put_contents("../fileStorage/$session_id/created.csv", "instrument,price,quantity,direction,datetime" );
?>

<input id="showFormButton" type="button" value="Enter first transaction" onclick="displayTransactionForm()"/>
<div id="transactionForm"></div>
<table id="transactionTable">
    <tr><th>Instrument</th>
        <th>Qty</th>
        <th>Direction</th>
        <th>Price</th>
        <th>Date</th>
    </tr>
</table>
<div id="transactionsList"></div>
<a href="../index.php?created=true">
    <button>Submit transaction list</button>
</a>