<?php
if(isset($companyDetailsArray)) {
    ?>
    <div class="companyContainer">
        <div class="companyHeader">
            <div class="companyName">
                <div class="companyStatusTooltip"><?=$companyDetailsArray["status"]?></div>
                <?=$companyDetailsArray["name"]?>
            </div>
            <div class="companyIncorporationDate">Incorporated on <?=$companyDetailsArray["date"]?></div>
        </div>
        <div class="companyInformation">
            <div class="generalCompanyInformation">
                <table class="companyInformationTable">
                    <tr>
                        <td>SIC code<?=isset($companyDetailsArray["SicCode2"])?"s":""?></td>
                        <td>
                            <div class="sicCodes">
                                <?php
                                $sicCodes = array($companyDetailsArray["SicCode1"] , $companyDetailsArray["SicCode2"] ,
                                    $companyDetailsArray["SicCode3"] , $companyDetailsArray["SicCode4"]);
                                foreach($sicCodes as $sicCode){
                                    if(is_null($sicCode)) continue;
                                    ?>
                                    <div class='sicCode'><div class='sicTooltip'><?=companyDatabaseModel::findSicCodeDescription($sicCode)?></div>
                                        <a href="?sic=<?=$sicCode?>&action=screen-sic"><?=$sicCode?></a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Company number</td><td><?=$companyDetailsArray["number"]?></td>
                    </tr>
                    <tr>
                        <td>Company category</td><td>
                            <div class="companyCategory">
                        <?php
                        if(preg_match("/(.*) \((.*?)\)/", $companyDetailsArray["category"], $category)) {
                            $companyDetailsArray["category"] = $category[1];
                            echo "<div class='companyCategoryTooltip'>$category[2]</div>";
                        }
                        ?>
                        <?=$companyDetailsArray["category"]?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Company status</td><td><?=$companyDetailsArray["status"]?></td>
                    </tr>
                </table>
                <table class="companyFinancialTable">
                    <?php
                    if(isset($companyDetailsArray["financials"])) {
                        $units = array("GBP"=>"£", "USD"=>"\$", "EUR"=>"€");
                        $formattedFinancials = [];
                        $years = [];
                        foreach ($companyDetailsArray["financials"] as $financial) {
                            $curPrev = $financial["interpretation"] == $financial["date"] ? "current" : "previous";
                            $label = trim(preg_replace("/[A-Z][a-z]/", " $0", $financial["label"]));
                            $formattedFinancials[$curPrev][$label]["year"] = date("Y", strtotime($financial["interpretation"]));
                            $formattedFinancials[$curPrev][$label]["value"] = $units[$financial["unit"]] . number_format($financial["value"]);
                            $years[$curPrev] = $formattedFinancials[$curPrev][$label]["year"];
                        }
                        ?>
                        <tr><td></td>
                            <th><span class="financialYear"><?=$years["current"]?></span></th>
                            <?php if(isset($years["previous"])){ ?><th><span class="financialYear"><?=$years["previous"]?></span></th><?php } ?>
                        </tr>
                        <?php
                        foreach($formattedFinancials["current"] as $formattedFinancialLabel=>$formattedFinancialDetails){
                            if(isset($formattedFinancials["previous"]["$formattedFinancialLabel"])){
                            $tooltip = $formattedFinancials["previous"]["$formattedFinancialLabel"]["value"];
                            }
                            ?>
                            <tr class="companyFinancialRow">
                                <td><div class="companyFinancialLabel"><?=$formattedFinancialLabel?>
                                        </div></td>
                                <td><?= $formattedFinancialDetails["value"] ?></td>
                                <?=isset($tooltip)? "<td>$tooltip</td>":"" ?>
                            </tr>
                            <?php
                            unset($tooltip);
                        }
                    }
                        ?>
                </table>
            </div>
            <div class="companyAddress">
                <div class="companyBoldTitle">Address</div>
                <div class="companyLocality"><?=$companyDetailsArray["county"]?></div>
                <div class="companyCountry"><?=$companyDetailsArray["country"]?></div>
                <div class="companyLocality"><?=$companyDetailsArray["postCode"]?></div>
            </div>
            <div class="companyPersonsContainer">
                <div class="companyBoldTitle">Persons with significant control</div>
                <ul class="companyPersons">
                    <?php
                    foreach($companyDetailsArray["people"] as $person){
                        $link = "?action=persons-companies&firstName=".$person["firstName"]."&lastName=".$person["lastName"].
                            "&birthMonth=".$person["birthMonth"]."&birthYear=".$person["birthYear"];
                        ?>
                        <li><a class="darker" href="<?=$link?>"><?=$person["firstName"]?> <?=$person["lastName"]?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>

        </div>




        <div class="companyResponseTime">Link to <a href="https://companies-house-frontend-api-rmfuc.ondigitalocean.app/company/<?=$companyDetailsArray["number"]?>" target="_blank">Digital Ocean</a></div>

        <div class="companyResponseTime">SQL queried in <?=round($companyDetailsArray["response_time"], 4)*1000?>ms</div>
    </div>

    <?php
}