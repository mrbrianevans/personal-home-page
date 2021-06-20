<?php
?>
<form method="get" action="request_handler.php" class="green">
    <label for="newcontestname">Contest name</label>
    <input type="text" id="newcontestname" name="newcontestname">
    <label for="newcontesttypeoptions">Prediction data type</label>
    <select id="newcontesttypeoptions" name="newcontesttype">
        <option value="prediction_string">String</option>
        <option value="prediction_int">Number</option>
        <option value="prediction_date">Date/time</option>
    </select>
    <input type="submit" value="New Contest" name="newcontest">
</form>

