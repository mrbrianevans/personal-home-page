<?php
if(isset($companyDetailsArray)) {
    ?>
    <div>
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
                                    echo "<div class='sicCode'>$sicCode";
                                        echo "<div class='sicTooltip'>$sicCode</div>"; //TODO: Get the description here
                                    echo "</div>";
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
            </div>
            <div class="companyAddress">
                <div class="companyAddressTitle">Address</div>
                <div class="companyLocality"><?=$companyDetailsArray["county"]?></div>
                <div class="companyCountry"><?=$companyDetailsArray["country"]?></div>
                <div class="companyLocality"><?=$companyDetailsArray["postCode"]?></div>
            </div>
        </div>
<div class="companyResponseTime">result returned in <?=round($companyDetailsArray["response_time"], 4)?> seconds</div>
    </div>

    <?php
}