<?php
$sender = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
if(isset($_GET['ip'])){
    if ($sender=="brianevans.tech"||$sender=="www.brianevans.tech"){ // checks that the request is internal
        require_once('adminModel.php');
        $model = new AdminModel();
        echo "<p class='smallhead'>Traffic from " .$_GET['ip']. "</p>";
        $visits = $model->getVisitsByIP($_GET['ip']);
        arsort($visits);
        echo "<p>Unique pages requested by $_GET[ip]: " . count($visits) . "</p>";
        echo "<table class='analytics'>";
        echo "<th>Page</th><th>Visits</th>";
        foreach ($visits as $page => $requests) {
            echo "<tr>";
            echo "<td>$page</td>";
            echo "<td>$requests </td>";
            echo "<tr>";
        }
        echo "</table>";
    }else{
        echo "Not authorised";
    }
}


if(isset($_GET['username'])){
    if ($sender=="brianevans.tech"||$sender=="www.brianevans.tech"){ // checks that the request is internal
        $username = $_GET['username'];
        if($username=="not logged in")
            $username = "";
        require_once('adminModel.php');
        $model = new AdminModel();
        echo "<p class='smallhead'>Traffic from " .$_GET['username']. "</p>";
        $visits = $model->getExactVisitsByUsername($username);
        arsort($visits);
        ?>
        <p>Total requests by <?=$username?>: <?=count($visits)?></p>
        <table class='analytics'>
        <tr>
            <th>Visit ID</th>
            <th>Date</th>
            <th>IP Address</th>
            <th>Location</th>
            <th>Username</th>
            <th>Page</th>
            <th>Previous ID</th>
            <th>Session</th>
        </tr>
    <?php
        foreach ($visits as $visitID => $visitInfo) {
            ?>
        <tr>
            <td><?=$visitID?></td>
            <td><?=$visitInfo['date_visited']?></td>
            <td><?=substr($visitInfo['ip_address'], 0, 15)?></td>
            <td><?=geoip_record_by_name($visitInfo['ip_address'])["city"] .", ".  geoip_record_by_name($visitInfo['ip_address'])["country_code"]?></td>
            <td><?=$visitInfo['name']?></td>
            <td><?=$visitInfo['page']?></td>
            <td><?=$visitInfo['previous']?></td>
            <td><a href="?chain=<?=$visitID?>"><?=$visitInfo['session_id']?></a></td>
        </tr>
            <?php
        }
    ?>
        </table>
        <?php
    }else{
        echo " Not authorised";
    }
}