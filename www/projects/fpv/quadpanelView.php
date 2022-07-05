<?php
$built = $quad["built"] ? "real" : "fantasy";
if(!$quad["deleted"]){
?>
<div id="panel<?=$quad["id"]?>" class="quadPanel <?=$built?>">
<?php
$plainArrows = file_get_contents("images/expand_arrows.svg");
$taggedArrows = str_replace("expand_arrows", "expand_arrows".$quad["id"], $plainArrows);
$eventArrows = str_replace("onclick=\"\"", "onmousedown=\"displayDetails('".$quad["id"]."')\"", $taggedArrows);
echo $eventArrows;
echo "Created quad on " . date("j M Y", strtotime($quad["creation_timestamp"]));

$classes = json_decode(file_get_contents("parts/class.json"));
$classRanking = 0.5;
$classMotorMin = 0;
$classMotorMax = 500;
$classMotorOpt = "";
$classESCMin = 0;
$classESCMax = 100;
$classESCOpt = "";
$classMotorLow = "";
$classMotorHigh= "";
$classESCLow = "";
$classESCHigh = "";
foreach($classes as $class){
    if($class->name==$quad["class"]) {
        $classRanking = $class->ranking;
        $classMotorMin = $class->motorSizeMinimum;
        $classMotorMax = $class->motorSizeMaximum;
        $classMotorOpt = $class->motorSizeOptimum;
        $classESCMin = $class->escAmpMinimum;
        $classESCMax = $class->escAmpMaximum;
        $classESCOpt = $class->escAmpOptimum;
        $classMotorLow = $class->motorSizeLow;
        $classMotorHigh = $class->motorSizeHigh;
        $classESCLow = $class->escAmpLow;
        $classESCHigh = $class->escAmpHigh;
    }
}
preg_match("/f([1347])/si", $quad["flight_controller"], $flightControllerRegExResults);
$flightControllerNumber = $flightControllerRegExResults[1];
$motorSizeCombined = (int)substr($quad["motor_size"], 0, 2) * (int)substr($quad["motor_size"], 2, 2);

?>
<div class="componentsContainer">


    <div class="svgContainer">
        <?=str_replace("quadSVG", "quadSVG".$quad["id"], file_get_contents("images/5 inch.svg"))?>
    </div>
    <div>
        <div class="quadComponentText" id="class<?=$quad["id"]?>">
            <?= $quad["class"] . " "?>
        </div>
        <div class="quadComponentText" id="motor<?=$quad["id"]?>">
            <?= $quad["motor_size"] . " " . $quad["motor_kv"] . "kV"?>
            <meter value="<?=$motorSizeCombined?>" min="<?=$classMotorMin?>" low="<?=$classMotorLow?>"
                   optimum="<?=$classMotorOpt?>" high="<?=$classMotorHigh?>" max="<?=$classMotorMax?>"></meter>
        </div>
        <div class="quadComponentText" id="esc<?=$quad["id"]?>">
            <?= $quad["esc_brand"] . " " .$quad["esc_amps"] . "A"?>
            <meter value="<?=$quad["esc_amps"]?>" min="<?=$classESCMin?>" max="<?=$classESCMax?>" optimum="<?=$classESCOpt?>"
            low="<?=$classESCLow?>" high="<?=$classESCHigh?>"></meter>
        </div>
        <div class="quadComponentText" id="flightController<?=$quad["id"]?>">
            <?= $quad["flight_controller"] . ""?>
            <meter value="<?=$flightControllerNumber?>" min="0" max="7" optimum="6" low="3" high="5"></meter>
        </div>
        <div class="quadComponentText" id="frame<?=$quad["id"]?>">
            <?= $quad["frame"] . " frame"?>
        </div>
    </div>
</div>

</div>

<?php } ?>