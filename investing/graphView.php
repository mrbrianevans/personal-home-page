<?php
    //Only import the script if displaying the graph
?>

<script type="text/javascript" src="betaGrapher.js"></script>
<?php
if(isset($portfolioStats)){
    ?>
    <table id="portfolioStatsTable">
        <tr>
            <td>
                Number of instruments: <?=number_format($portfolioStats["no_of_instruments"])?>
            </td>
            <td>
                Length of period: <?=number_format($portfolioStats["days_in_range"])?> days
            </td>
            <td>
                Time to calculate graphs: <?=number_format($portfolioStats["calcs_time"], 4)?> seconds
            </td>
        </tr>
    </table>
<?php
}
?>

<div id="portfolioGraph">
</div>
<a href="index.php?newupload=true"><button id="newUploadButton">Upload a new file</button></a>
<p class="smallhead">Views:</p>
<button onclick="drawTimeWeightedRateOfReturn()">Rate of Return</button>
<button onclick="drawInstrumentsValue()">Instruments Value</button>
<button onclick="drawPortfolioValue()">Portfolio Value</button>