<?php
require_once "shapeObject.php";

$circle = new circleObject(200);
$circle->setFillColor("green");
$circle->setStrokeColor("whitesmoke");
$circle->setStrokeWidth(10);

$square = new rectangleObject(200, 200);
$square->setFillColor("tomato");
$square->setStrokeColor("antiquewhite");
$square->setStrokeWidth(20);

echo "<svg width='500' height='500'>";

echo $circle->getCircleTag();
echo $square->getRectangleTag();

echo "</svg>";