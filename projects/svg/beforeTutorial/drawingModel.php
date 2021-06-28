<?php


class drawingModel
{

}

class shapesObject
{
    private string $fillColor;
    private string $strokeColor;
    private string $strokeWidth;
    private string $onHoverFillColor;
    private string $onClick;
    protected string $id;

    private function getOnHoverAttributes(){
        return ($this->getOnHoverFillColor()) ?
            "onmouseover=\"changeColor('$this->id', '". $this->getOnHoverFillColor() ."')\"
             onmouseout=\"changeColor('$this->id', '" . $this->getFillColor() . "')\""
            :"";
    }
    /**
     * @return string
     */
    public function getOnClick(): string
    {
        return $this->onClick;
    }

    /**
     * @param string $onClick
     */
    public function setOnClick(string $onClick): void
    {
        $this->onClick = $onClick;
    }
    public function getCSS(){
        return "
        fill: " . $this->getFillColor() . "; 
        stroke: " . $this->getStrokeColor() . "; 
        stroke-width: " . $this->getStrokeWidth() . "; ";
    }
    /**
     * @param string $strokeColor
     */
    public function setStrokeColor(string $strokeColor): void
    {
        $this->strokeColor = $strokeColor;
    }

    /**
     * @return string
     */
    public function getFillColor(): string
    {
        return $this->fillColor??'';
    }

    /**
     * @return string
     */
    public function getStrokeColor(): string
    {
        return $this->strokeColor??'';
    }

    /**
     * @return string
     */
    public function getStrokeWidth(): string
    {
        return $this->strokeWidth??'';
    }

    /**
     * @return string
     */
    public function getOnHoverFillColor(): string
    {
        return $this->onHoverFillColor??'';
    }

    /**
     * @param string $strokeWidth
     */
    public function setStrokeWidth(string $strokeWidth): void
    {
        $this->strokeWidth = $strokeWidth;
    }

    /**
     * @param string $onHoverFillColor
     */
    public function setOnHoverFillColor(string $onHoverFillColor): void
    {
        $this->onHoverFillColor = $onHoverFillColor;
    }

    /**
     * @param string $fillColor
     */
    public function setFillColor(string $fillColor): void
    {
        $this->fillColor = $fillColor;
    }
}

class squaresObject extends shapesObject
{
    private int $length;
    public function __construct($length)
    {
        $this->length = $length;
    }
}

class circlesObject extends shapesObject
{
    private int $radius;
    public function __construct($radius)
    {
        $this->radius = $radius;
        try {
            $this->id = chr(random_int(97, 122));
            for($i = random_int(0, 10); $i < random_int(10, 20); $i++){
                $this->id .= chr(random_int(97, 122));
            }
        } catch (Exception $e) {
            $this->id = $e;
        }
    }
    public function changeRadius($newRadius){
        $this->radius = $newRadius;
    }

    public function getCircleSVG()
    {
        $size = 3*$this->radius;
        $center = $size/2;
        $css = $this->getCSS();
        $svgOpen = "<svg width='$size' height='$size' style='border: 1px solid black'>";
        $id = $this->id;
        $hoverAttributes = ($this->getOnHoverFillColor()) ?
            "onmouseover=\"changeColor('$id', '". $this->getOnHoverFillColor() ."')\"
             onmouseout=\"changeColor('$id', '" . $this->getFillColor() . "')\""
            :"";
        $circleTag = "<circle id='$id' 
                        $hoverAttributes 
                        onclick='move(\"$id\", 10, 0)'
                        cx='$center' cy='$center' r='$this->radius' 
                        style='$css'/>";
        $svgClose = "</svg>";
        return $svgOpen . $circleTag . $svgClose;
    }
}
class rectangleObject{

}