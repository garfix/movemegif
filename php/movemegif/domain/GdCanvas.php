<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class GdCanvas implements Canvas
{
    /** @var resource  */
    private $resource;

    public function __construct($width, $height)
    {
        $this->resource = imagecreate($width, $height);
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function getPixels($clipLeft, $clipTop, $clipRight, $clipBottom)
    {
        $pixels = array();
        $index2color = array();

        for ($y = $clipTop; $y <= $clipBottom; $y++) {
            for ($x = $clipLeft; $x <= $clipRight; $x++) {

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