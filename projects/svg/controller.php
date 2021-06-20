<?php
require_once "shapeObject.php";

$circle = new circleObject(200);
$circle->setFillColor("green");
$circle->setStrokeColor("whitesmoke");
$circle->setStrokeWidth(10);
$circle->setHoverColor("forestgreen");

$square = new rectangleObject(200, 200);
$square->setFillColor("tomato");
$square->setStrokeColor("antiquewhite");
$square->setStrokeWidth(20);

echo "<button onclick='moveCircle(\"".$circle->getID()."\", 20, 20)'>Move circle down&right 2o pixels</button>";

echo "<svg width='500' height='500'>";

echo $circle->getCircleTag();
echo $square->getRectangleTag();

echo "</svg>";