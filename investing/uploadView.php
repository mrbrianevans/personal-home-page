<?php
?>
<form action="index.php" method="post" enctype="multipart/form-data">
    <label>Please upload your full transaction history in CSV format with these columns:</label>
    <table class="analytics">
        <tr>
            <th>instrument</th>
            <th>quantity</th>
            <th>direction</th>
            <th>price</th>
            <th>datetime</th>
        </tr>
        <tr id="columns-example">
            <td>SNAP</td>
            <td>90</td>
            <td>buy</td>
            <td>21.43</td>
            <td>31.05.2014</td>
        </tr>
    </table>
    <input type="file" name="csvUpload" id="csvUpload"/>
    <input type="submit" name="uploadButton" value="Upload"/>
    <p>
        The instrument column should contain the ticker (only NYSE supported thus far).<br>
        The datetime column should be either dd.mm.yyyy or mm/dd/yyyy (with or without time hh:mm:ss)<br>
        Here is an example: <a href="example.csv">example.csv</a> <br>
        For more information about CSV formatting, see this page: <a href="csv-formatting.php">CSV Formatting</a>
    </p>
</form>

<a href="index.php?samplePortfolio=true"><button id="samplePortfolio">View a sample portfolio</button></a>
<a href="csv-builder"><button>Create a CSV from transactions</button></a>