<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class GdCanvas implements Canvas
{
    private $resource;

    public function __construct($width, $height)
    {
        $this->resource = imagecreate($width, $height);
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getPixels()
    {
        $width = imagesx($this->resource);
        $height = imagesy($this->resource);

        $pixels = array();

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $index = imagecolorat($this->resource, $x, $y);
                $rgb = imagecolorsforindex($this->resource, $index);
                $pixels[] =
                    ($rgb['red'] << 16) +
                    ($rgb['green'] << 8) +
                    ($rgb['blue']);
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