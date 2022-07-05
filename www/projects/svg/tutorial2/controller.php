<?php
require_once "shapeObject.php";

$circle = new circleObject(100);

echo "<svg width='500' height='500'>";

echo $circle->getCircleTag();

echo "</svg>";