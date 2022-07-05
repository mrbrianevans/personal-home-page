<?php
require "drawingModel.php";

$circ1 = new circleObject(150);
$circ1->setFillColor("green");
$circ1->setStrokeWidth(5);
$circ1->setStrokeColor("white");
$circ1->setOnHoverFillColor("lightgreen");
echo $circ1->getCircleSVG();

$circ2 = new circleObject(50);
$circ2->setFillColor("red");
$circ2->setOnHoverFillColor("tomato");
echo $circ2->getCircleSVG();