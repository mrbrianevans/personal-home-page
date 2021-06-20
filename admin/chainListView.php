<?php
if(isset($chainList)){
    ?>

    <table class="fullWidth bordered">
        <tr>
            <th>Date</th>
            <th>Location</th>
            <th>Request Qty</th>
            <th>Session length</th>
            <th>Start time</th>
            <th>Finish time</th>
            <th>Pages</th>
        </tr>
    <?php
        foreach($chainList as $chain){
            ?>
            <tr>
                <td><?=date("j M Y",$chain["start"])?></td>
                <td><?=$chain["location"]?></td>
                <td><?=$chain["requests"]?></td>
                <td><?=number_format($chain["length"])?> seconds</td>
                <td><?=date("g:ia",$chain["start"])?></td>
                <td><?=date("g:ia",$chain["finish"])?></td>
                <td><?php
                    $limit = 3;
                    $count = 0;
                    foreach($chain["pages"] as $page=>$visits){
                        if($count++==$limit) break;
                        echo "<li>$page [$visits visits]</li>";
                    } unset($page, $visits, $limit, $count);
                    ?></td>
                <td><a href="?chain=<?=$chain["session"]?>"><button>View</button></a></td>
            </tr>
            <?php
        }
    ?>
    </table>
    <?php
}

