<p>View past contest <a href="results" class="darker">results</a> </p>
<p class="smallhead" style="text-align: left">Enter a contest</p>
<div>
    <table class="contest-entry">
        <tr>
            <th>Contest Name</th>
            <th>Date started</th>
            <th>Action</th>
        </tr>

    <?php
    foreach ($contests as $label=>$contest) {
        ?>

        <tr>
            <td><?=$contest['contest_name'] ?></td>
            <td><?=date("j M", strtotime($contest['date_started'])) ?></td>
            <td id="<?=$contest['contest_id'] ?>td">
                <button class='green' onclick="showEntryBox('<?=$contest['contest_id'] ?>', '<?= $entry_buttons[$contest['contest_name']] ?>')">
                    <?= $entry_buttons[$contest['contest_name']] ?></button>
            </td>
        </tr>

        <?php
    }
    ?>

    </table>
</div>

<p class="smallhead" style="text-align: left">Full view of submissions</p>
<div class="predictionscontainer"><?php
    ?>
    <?php
    foreach ($contests as $label=>$contest){
        ?>
        <div class="contests">
            Contest: <strong><?=$contest['contest_name']?></strong>
            <?php
            if($contest['type'] == "prediction_date")
                echo "<br>Enter date in any format :)";
            ?>
            <table class="contest-entries">
                <tr>
                    <th>Person</th>
                    <th>Date of entry</th>
                    <th>Prediction</th>
                </tr>
            <?php
            foreach($contest_details[$contest['contest_name']] as $row){

            ?>
                    <tr>
                        <td><?=$row['username']?></td>
                        <td><?php if($uname=="brianevans") echo $row['datetime']; else echo date("j M", strtotime($row['datetime'])); ?></td>
                        <td id="<?=$row['username'].$contest['contest_id']?>">
                            <?=$row['prediction_string'] . $row['prediction_int']?>
                            <?php if(strtotime($row['prediction_date'])) echo date("j M Y", strtotime($row['prediction_date'])) ?>
                        </td>
                    </tr>
                    <?php
                    }
            unset($row);
                ?>
            </table>
        </div>
    <?php
    }
    unset($contests);
    ?>

</div>