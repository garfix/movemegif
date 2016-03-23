<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class Image
{
    private $pixels = null;

    /**
     * Enter this image's data as a string of indexes and a indexed color table.
     *
     * For example:
     *
     * $pixels = "
     *     1 1 2 2
     *     1 0 0 2
     *     2 2 1 1
     * ";
     *
     * $colors = array(
     *     0 => 0xFFFFFF,
     *     1 => 0xFF0000,
     *     2 => 0x0000FF
     * )
     *
     * Each color is coded as 0x00RRGGBB (unused, red, green, blue bytes)
     *
     * @param string $pixels A whitespace separated string of color indexes.
     * @param int[] $colors An index 2 RGB color map.
     */
    public function setPixelsAndColors($pixels, array $colors, $width)
    {
        $string = $pixels;

        preg_match_all('/(\d+)/', $pixels, $matches);

        foreach ($matches as $match) {

            $index = $match[0];

            if (array_key_exists($index, $colors)) {
                $string .= pack('V', $colors[$index]);
            } else {
#todo
            }

        }

        $this->pixels = $string;
    }

    public function getPixels()
    {
        if ($this->pixels === null) {
            return
                "1 1 1 1 1 2 2 2 2 2 " .
                "1 1 1 1 1 2 2 2 2 2 " .
                "1 1 1 1 1 2 2 2 2 2 " .
                "1 1 1 0 0 0 0 2 2 2 " .
                "1 1 1 0 0 0 0 2 2 2 " .
                "2 2 2 0 0 0 0 1 1 1 " .
                "2 2 2 0 0 0 0 1 1 1 " .
                "2 2 2 2 2 1 1 1 1 1 " .
                "2 2 2 2 2 1 1 1 1 1 " .
                "2 2 2 2 2 1 1 1 1 1";

        } else {
            return $this->pixels;
        }
    }
}