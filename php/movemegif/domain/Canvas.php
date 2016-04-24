<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
abstract class Canvas
{
    /** @var int|null The 0xRRGGBB color that specifies the pixels that will be made transparent (null = none) */
    protected $transparencyColor = null;

    public abstract function getWidth();

    public abstract function getHeight();

    public abstract function getPixels($clipLeft, $clipTop, $clipRight, $clipBottom);

    /**
     * @param int $color A color like 0xRRGGBB
     */
    public function setTransparencyColor($color)
    {
        $this->transparencyColor = $color;
    }

    public function getTransparencyColor()
    {
        return $this->transparencyColor;
    }
}