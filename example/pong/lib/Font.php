<?php

namespace pong\lib;

use movemegif\domain\GdCanvas;

/**
 * @author Patrick van Bergen
 */
class Font
{
    /**
     * @param GdCanvas $canvas
     * @param string $letter A single letter
     * @param int $x
     * @param int $y
     * @param int $pixelSize Size of a single dot, in pixels
     * @param int $color A GD color reference
     */
    public function drawLetter(GdCanvas $canvas, $letter, $x, $y, $pixelSize, $color)
    {
        $glyph = $this->getCrudeFontForLetter($letter);

        foreach ($glyph as $row => $chars) {
            for ($col = 0; $col < 3; $col++) {
                $isPixelSet = ($chars[$col] == '*');

                if ($isPixelSet) {

                    $x1 = $x + ($col * $pixelSize);
                    $y1 = $y + ($row * $pixelSize);
                    $x2 = $x1 + $pixelSize - 1;
                    $y2 = $y1 + $pixelSize - 1;

                    imagefilledrectangle($canvas->getResource(), $x1, $y1, $x2, $y2, $color);

                }
            }
        }
    }

    public function getCrudeFontForLetter($letter)
    {
        $font = array(
            'P' => array(
                '***',
                '* *',
                '***',
                '*  ',
                '*  ',
            ),
            'O' => array(
                '***',
                '* *',
                '* *',
                '* *',
                '***',
            ),
            'N' => array(
                '***',
                '* *',
                '* *',
                '* *',
                '* *',
            ),
            'G' => array(
                '***',
                '*  ',
                '* *',
                '* *',
                '***',
            ),
            '0' => array(
                '***',
                '* *',
                '* *',
                '* *',
                '***',
            ),
            '1' => array(
                '  *',
                '  *',
                '  *',
                '  *',
                '  *',
            ),
            '2' => array(
                '***',
                '  *',
                '***',
                '*  ',
                '***',
            ),
            '3' => array(
                '***',
                '  *',
                '***',
                '  *',
                '***',
            ),
        );

        return $font[$letter];
    }
}