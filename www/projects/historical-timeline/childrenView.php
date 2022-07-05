<?php
if(isset($children)){
    $numberOfChildren = count($children);
    if ($numberOfChildren % 5 == 0) {
        $width = "fifth";
    }elseif ($numberOfChildren % 4 == 0){
        $width = "quarter";
    }elseif ($numberOfChildren % 3 == 0){
        $width = "third";
    }elseif ($numberOfChildren % 2 == 0){
        $width = "half";
    }else{
        $width = "full";
    }
    ?>
    <h3>Children</h3>
    <div class="flex row wrap">
    <?php
    foreach ($children as $child) {
        ?>

        <div class="bordered padded <?=$width?>">
            <h3><?= $child["name"] ?></h3>
            <a href="?action=ancestry&id=<?= $child["id"] ?>">
                <button class="wide">View</button>
            </a>
        </div>

        <?php
    }
    ?></div><?php
}else{
    echo "<p>Sorry, no match found to display</p>";
}