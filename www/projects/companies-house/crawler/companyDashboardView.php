<?php
if(isset($companyDetailsArray)) {
    ?>
    <div>
        <div class="companyHeader">
            <div class="companyName">
                <div class="companyStatusTooltip"><?=$companyDetailsArray["company_status"]?></div>
                <?=$companyDetailsArray["CompanyName"]?>
            </div>
            <div class="companyIncorporationDate">Incorporated on <?=$companyDetailsArray["IncorporationDate"]?></div>
        </div>
        <div class="companyInformation">
            <div class="generalCompanyInformation">

                <table class="companyInformationTable">
                    <tr>
                        <td>SIC code<?=isset($companyDetailsArray["SICCodeSicText_2"])?"s":""?></td>
                        <td>
                            <div class="sicCodes">
                                <?php
                                $sicCodes = array($companyDetailsArray["SICCodeSicText_1"] , $companyDetailsArray["SICCodeSicText_2"] ,
                                    $companyDetailsArray["SICCodeSicText_3"] , $companyDetailsArray["SICCodeSicText_4"]);
                                foreach($sicCodes as $sicCode){
                                    preg_match("/^[0-9A-Z]+/", $sicCode, $sicNumber);
                                    if(isset($sicNumber[0])){
                                        echo "<div class='sicCode'>$sicNumber[0]";
                                        echo "<div class='sicTooltip'>$sicCode</div>";
                                        echo "</div>";
                                    }

                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Company number</td><td><?=$companyDetailsArray["CompanyNumber"]?></td>
                    </tr>
                    <tr>
                        <td>Company category</td><td><?=$companyDetailsArray["CompanyCategory"]?></td>
                    </tr>
                    <tr>
                        <td>Company status</td><td><?=$companyDetailsArray["CompanyStatus"]?></td>
                    </tr>
                </table>
            </div>
            <div class="companyAddress">
                <div class="companyAddressTitle">Address</div>
                <div class="companyLocality"><?=ucwords(strtolower($companyDetailsArray["RegAddressCounty"]))?></div>
                <div class="companyCountry"><?=ucwords(strtolower($companyDetailsArray["RegAddressCountry"]))?></div>
                <div class="companyLocality"><?=$companyDetailsArray["RegAddressPostCode"]?></div>
            </div>
        </div>
<div class="companyResponseTime">result returned in <?=round($companyDetailsArray["response_time"], 4)?> seconds</div>
    </div>

    <?php
}