<?php
abstract class shapeObject{
    private $fillColor;
    private $strokeColor;
    private $strokeWidth;

    /**
     * @return mixed
     */
    public function getFillColor()
    {
        return $this->fillColor;
    }

    /**
     * @param mixed $fillColor
     */
    public function setFillColor($fillColor): void
    {
        $this->fillColor = $fillColor;
    }

    /**
     * @return mixed
     */
    public function getStrokeColor()
    {
        return $this->strokeColor;
    }

    /**
     * @param mixed $strokeColor
     */
    public function setStrokeColor($strokeColor): void
    {
        $this->strokeColor = $strokeColor;
    }

    /**
     * @return mixed
     */
    public function getStrokeWidth()
    {
        return $this->strokeWidth;
    }

    /**
     * @param mixed $strokeWidth
     */
    public function setStrokeWidth($strokeWidth): void
    {
        $this->strokeWidth = $strokeWidth;
    }

}

class circleObject extends shapeObject{
    private $radius;

    /**
     * circleObject constructor.
     * @param $radius
     */
    public function __construct($radius)
    {
        $this->radius = $radius;
    }

    public function getCircleTag(){
        $center = $this->radius + 10;

        $css = "fill: " . $this->getFillColor() . ";
                stroke: " . $this->getStrokeColor() . ";
                stroke-width: " . $this->getStrokeWidth() . ";";

        $circleTag = "<circle cx='$center' cy='$center' r='$this->radius' style='$css'/>";

        return $circleTag;
    }

}

class rectangleObject extends shapeObject{
    private $width;
    private $height;

    /**
     * rectangleObject constructor.
     * @param $width
     * @param $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getRectangleTag()
    {
        $css = "fill: " . $this->getFillColor() . ";
                stroke: " . $this->getStrokeColor() . ";
                stroke-width: " . $this->getStrokeWidth() . ";";


        $rectangeTag = "<rect width='$this->width' height='$this->height' style='$css'/>";

        return $rectangeTag;
    }
}