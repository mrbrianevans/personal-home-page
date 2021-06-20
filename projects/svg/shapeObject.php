<?php
abstract class shapeObject{
    private $fillColor;
    private $strokeColor;
    private $strokeWidth;
    private $id;
    private $hoverColor;

    /**
     * @return mixed
     */
    public function getHoverColor()
    {
        return $this->hoverColor;
    }

    /**
     * @param mixed $hoverColor
     */
    public function setHoverColor($hoverColor): void
    {
        $this->hoverColor = $hoverColor;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setID()
    {
        try {
            $this->id = chr(random_int(97, 122));
            for($i=0; $i<10; $i++){
                $this->id .= chr(random_int(97, 122));
            }
        } catch (Exception $e) {
            $this->id = $e;
        }
    }

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
        $this->setID();
    }

    public function getCircleTag(){
        $center = $this->radius + 10;
        $css = "fill: " . $this->getFillColor() . ";
                stroke: " . $this->getStrokeColor() . ";
                stroke-width: " . $this->getStrokeWidth() . ";";

        $circleTag = "<circle id='".$this->getID()."' cx='$center' cy='$center' r='$this->radius' style='$css'
                        onmouseover=\"changeColor('".$this->getID(). "', '". $this->getHoverColor() . "')\"
                         onmouseout=\"changeColor('".$this->getID(). "', '". $this->getFillColor() . "')\"/>";

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
        $this->setID();
    }

    public function getRectangleTag()
    {
        $css = "fill: " . $this->getFillColor() . ";
                stroke: " . $this->getStrokeColor() . ";
                stroke-width: " . $this->getStrokeWidth() . ";";


        $rectangeTag = "<rect id='".$this->getID()."' width='$this->width' height='$this->height' style='$css'/>";

        return $rectangeTag;
    }
}