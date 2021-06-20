<?php if(isset($dailyVisits, $geoVisits)){ ?>
<script>
    let dailyVisitsData = JSON.parse('<?=$dailyVisits?>');
    let locationFrequencyData = JSON.parse('<?=$geoVisits?>');
</script>
<?php } ?>
<script src="dailyVisitsGrapher.js"></script>
<div id="dailyVisitsGraph">Daily visits graph</div>
<div id="mapOfVisitsContainer">Map of visits</div>
<label for="analyticsoptions">Analytics options? </label>
<select id="analyticsoptions" onchange="analyticschosen()" onmouseover="analyticschosen()">
    <option value="contacthistory">Contact messages</option>
    <option value="pagesbyip">Pages visited by a specific IP address</option>
    <option value="pagesbyusername">Pages visited by a specific username</option>
    <option value="trafficbyip">Traffic by IP address</option>
    <option value="trafficbypages">Traffic by page</option>
    <option value="trafficbyusername">Traffic by username</option>
    <option value="trafficbynetworkip">Traffic by network IP address</option>
</select>
<div id="contacthistory">
    <p class="smallhead">Contact form messages</p>
    <table class="contacttable" style="text-align: center; border: 1px solid #2F302F; border-collapse: collapse; padding: 25px 0">
        <th class="fit-to-content">IP ADDRESS</th>
        <th class="fit-to-content">Location</th>
        <th class="fit-to-content">Date</th>
        <th class="fit-to-content">Name</th>
        <th class="fit-to-content">Username</th>
        <th class="fit-to-content">Email address</th>
        <th style="width: available">Message</th>
    <?php foreach($contactmessages as $individualmessage){ ?>
        <tr>
            <td><?= $individualmessage['ip_address'] ?></td>
            <td><?=geoip_record_by_name($individualmessage['ip_address'])["city"] .", ".  geoip_record_by_name($individualmessage['ip_address'])["country_code"]?></td>
            <td><?= $individualmessage['date'] ?></td>
            <td><?= $individualmessage['name'] ?></td>
            <td><?= $individualmessage['username'] ?></td>
            <td><?= $individualmessage['email'] ?></td>
            <td><?= $individualmessage['message'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
<div id="pagesbyusername" hidden>
    <label for="pagesbyusernamedropdown">Username </label>
    <select id="pagesbyusernamedropdown" onchange="pagesbyusername()">
        <?php
        foreach($users as $user => $visits){
            ?>

            <option value="<?=$user?>"><?=$user?></option>

            <?php
        } ?>
    </select>
    <div id="usernamespecifichistory">

    </div>
</div>
<div id="pagesbyip" hidden>
<label for="pagesbyipdropdown">IP ADDRESS: </label>
<select id="pagesbyipdropdown" onchange="pagesbyip()" onmouseover="pagesbyip()">
    <?php
    foreach($ips as $visitor => $visits){
        ?>

        <option value="<?=$visitor?>"><?=$visitor?></option>

        <?php
    } ?>
</select>
<div id="specificiphistory">

</div>
</div>
<div id="trafficbyip" hidden>
    <p class="smallhead">Traffic by IP address</p>
    <table class="analytics">
        <th>IP ADDRESS</th>
        <th>Number of visits</th>
        <?php

        foreach($ips as $visitor => $visits){
            ?>
            <tr>
            <td><?=$visitor ?></td>
            <td><?=$visits ?></td>
            </tr>
            <?php
        } ?>
    </table>
    <hr>
</div>

<div id="trafficbypages" hidden>
    <p class="smallhead">Traffic by page </p>

    <table class="analytics">
        <th>IP ADDRESS</th>
        <th>Number of visits</th>
        <?php

        foreach($pages as $page => $visits){
            ?>
            <tr>
                <td><?=$page ?></td>
                <td><?=$visits ?></td>
            </tr>
            <?php
        } ?>
    </table>
    <hr>
</div>
<div id="trafficbyusername" hidden>
    <p class="smallhead">Traffic by username </p>
    <table class="analytics">
        <th>IP ADDRESS</th>
        <th>Number of visits</th>
        <?php

        foreach($users as $user => $visits){
            ?>
            <tr>
                <td><?=$user ?></td>
                <td><?=$visits ?></td>
            </tr>
            <?php
        } ?>
    </table>
    <hr>
</div>
<div id="trafficbynetworkip" hidden>
    <p class="smallhead">Traffic by network IP</p>
    <table class="analytics">
        <th>IP ADDRESS</th>
        <th>Number of visits</th>
        <?php

        foreach($networkip as $ip => $visits){
            ?>
            <tr>
                <td><?=$ip ?></td>
                <td><?=$visits ?></td>
            </tr>
            <?php
        } ?>
    </table>
    <hr>
</div>