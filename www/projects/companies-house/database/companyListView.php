<?php
if(isset($companyList)) {
//    print_r($companyList);
    if(count($companyList)) {
        ?>
        <link type="text/css" href="companyListStyles.css" rel="stylesheet"/>
        <div class="companyListContainer">
            <table class="companyList">
                <tr>
                    <th>Company name</th>
                    <th>SIC codes</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Persons with SC</th>
                    <th>Financials saved?</th>
                    <?php
                    $columnLimit = 3;
                    $columnCount = 0;
                    foreach ($companyList["columns"] as $columnName => $columnFrequency) {
                        if ($columnCount++ >= $columnLimit) break;
                        $columnName = companyDatabaseModel::capitaliseLabel($columnName);
                        echo "<th>$columnName</th>";
                    }
                    ?>
                </tr>
                <?php
                foreach ($companyList as $companyDetailsArray) {
                    if (!is_array($companyDetailsArray)) continue;
                    if (!isset($companyDetailsArray["name"])) continue;
                    $companyNumber = $companyDetailsArray["number"];
                    $companyName = ucwords(strtolower($companyDetailsArray["name"]));
                    $companyDetailsArray["name"] = "<a href='?action=search&number=$companyNumber'>$companyName</a>";
                    $age = round((time() - strtotime($companyDetailsArray["date"])) / (86400 * 365));
                    ?>
                    <tr class="companyInList">
                        <td><?= $companyDetailsArray["name"] ?></td>
                        <td><div class="sicCodes"><?php
                            foreach(array($companyDetailsArray["SicCode1"], $companyDetailsArray["SicCode2"],
                                $companyDetailsArray["SicCode3"], $companyDetailsArray["SicCode4"]) as $sicCode){
                                if(strlen($sicCode)){
                                    ?>
                            <div class='sicCode'><div class='sicTooltip'><?=companyDatabaseModel::findSicCodeDescription($sicCode)?></div>
                            <a href="?sic=<?=$sicCode?>&action=screen-sic"><?=$sicCode?></a>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            </div></td>
                        <td><?= $age ?> years</td>
                        <td><?=$companyDetailsArray["county"]?></td>
                        <td><?=count($companyDetailsArray["people"])?></td>
                        <td><?=count($companyDetailsArray["financials"])?"Yes":"No"?></td>
                        <?php
                        $columnCount = 0;
                        foreach ($companyList["columns"] as $columnName => $columnFrequency) {
                            if ($columnCount++ >= $columnLimit) break;
                            echo "<td>";
                            foreach ($companyDetailsArray["financials"] as $financial) {
                                echo $financial["label"] == $columnName && $financial["date"] == $financial["interpretation"] ? $financial["value"] . " " : "";
                            }
                            echo "</td>";
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <?php
    }else{
        echo "<p>Zero companies match that search</p>";
    }
}else{
    echo "<div>Company list not available</div>";
}
