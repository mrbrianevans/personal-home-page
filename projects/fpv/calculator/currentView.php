<?php
    if(isset($scatterChartPoints, $columnChartBars, $newCurrentScale)) {
        ?>
        <script src="../../../googleChartsLocal.js"></script>
        <script src="graphDrawer.js"></script>
        <script>
            let scatterData = JSON.parse('<?=$scatterChartPoints?>');
            let columnData = JSON.parse('<?=$columnChartBars?>');
        </script>

        <div id="newCurrentSuggestion">Your current scale should be set to about <span id="newCurrent"><?= $newCurrentScale ?></span></div>
        <div id="doubleGraphContainer">
            <div id="scatterChartContainer"></div>
            <div id="columnChartContainer"></div>
        </div>

        <div id="tableContainer"></div>
        <?php
    }