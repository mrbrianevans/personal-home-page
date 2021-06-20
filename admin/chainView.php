<?php
    if(isset($chain)) {
        ?>

        <ol>
            <?php
            foreach($chain as $visit){
                echo "<li>" . $visit["page"] . " at " . date("g:ia", strtotime($visit["date_visited"]))."</li>";
            }
            ?>
        </ol>

        <?php
    }