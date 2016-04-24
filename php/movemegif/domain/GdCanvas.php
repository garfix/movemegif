<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class GdCanvas extends Canvas
{
    /** @var resource  */
    protected $resource;

    public function __construct($width, $height)
    {
        $this->resource = imagecreate($width, $height);
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getPixels($clipLeft, $clipTop, $clipRight, $clipBottom)
    {
        $pixels = array();
        $index2color = array();

        $top = max(0, $clipTop);
        $left = max(0, $clipLeft);

        $bottom = min($this->getHeight() - 1, $clipBottom);
        $right = min($this->getWidth() - 1, $clipRight);

        for ($y = $top; $y <= $bottom; $y++) {
            for ($x = $left; $x <= $right; $x++) {

                $index = imagecolorat($this->resource, $x, $y);

                if (!isset($index2color[$index])) {

                    $rgb = imagecolorsforindex($this->resource, $index);
                    $index2color[$index] = ($rgb['red'] << 16) + ($rgb['green'] << 8) + ($rgb['blue']);
                }

                $color = $index2color[$index];

                $pixels[] = $color;
            }
        }

        return $pixels;
    }

    public function getWidth()
    {
        return imagesx($this->resource);
    }

    public function getHeight()
    {
        return imagesy($this->resource);
    }
}