<?php
if(isset($person)){
    $skipHeadings = array("", "notified", "companyNumber", "link");

    $finalisedValues = [];
    $allInfoTooltip = "<h3>All available information:</h3><ul>";
    foreach($person as $personAttribute=>$frequencyTable){
        if (array_search($personAttribute, $skipHeadings)) continue;
        $personAttribute = companyDatabaseModel::capitaliseLabel($personAttribute);
        unset($frequencyTable[""]);
        if(count($frequencyTable)==0) continue;
        if(count($frequencyTable)>5&&array_sum($frequencyTable)==count($frequencyTable)) continue;
        if(count($frequencyTable)==1){
            $allInfoTooltip .= "<li>$personAttribute = " . array_key_first($frequencyTable) . "</li>";
            $finalisedValues[$personAttribute] = array_key_first($frequencyTable);
        }else{
            $allInfoTooltip .= "<li>$personAttribute's:<ol>";
            arsort($frequencyTable);
            foreach($frequencyTable as $value=>$frequency){
                $allInfoTooltip .= "<li>$value ($frequency)</li>";
            }
            $allInfoTooltip .= "</ol></li>";
        }
    }
    $allInfoTooltip .= "</ul>";
    $name = $finalisedValues["First Name"] . " " . $finalisedValues["Middle Name"] . " " . $finalisedValues["Last Name"];
    $monthSlashYear = str_pad($finalisedValues["Birth Month"], 2, "0", STR_PAD_LEFT)
        . "/01/" . $finalisedValues["Birth Year"];
    $birthDate = date("F Y", strtotime($monthSlashYear));
    ?>
    <link rel="stylesheet" href="personPageStyles.css" type="text/css"/>
    <div class="personDetailsShell">
        <div class="personDetailSummary">
            <div><?=$name?><?=" (".$finalisedValues["Nationality"].")"?></div>
            <div></div>
            <div><?=$finalisedValues["Address Region"].", ".$finalisedValues["Country Of Residence"]?></div>
            <div><?=$birthDate?></div>
            <div class="personDetailsTooltip"><?=$allInfoTooltip?></div>
        </div>
    </div>
    <?php
}