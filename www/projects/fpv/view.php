<?php
if (isset($errors)) {
    foreach($errors as $error){
        echo "<div class='errorMessage'>";
        echo "<b>".$error["name"]."</b>";
        echo "<br>" . $error["message"];
        echo "</div>";
    }
}else{
?>
<h2>Quadcopter ID: <?=$_SESSION["quad_id"]?></h2>
<p>

    <br>
    Weight: <?=$quad["dry_weight"]?>
    <br>
    Throttle MID: <?=$quad["throttle_hover"]?>
    <br>
    <?=$quad["motor_size"]?> <?=$quad["motor_kv"]?>kV <?=$quad["motor_brand"]?> motors
    <br>
    <?=$quad["esc_amps"]?>A <?=$quad["esc_brand"]?> 4in1 ESC
    <br>
    Date created: <?=date("j M, Y",strtotime($quad["creation_timestamp"]))?>
</p>

<?php }  ?>

<a href="?new=true">New quadcopter</a>

<a href="calculator" class="darker">Current calibration</a>