<?php
if(isset($addedSons)){
    foreach($addedSons as $addedSon=>$id){
    ?>

        <div style="display: inline-block" class="fifth">
            <a href="?action=ancestry&id=<?= $id ?>">
                <button class="full"><?= $addedSon ?></button>
            </a>
        </div>

    <?php
    }
}