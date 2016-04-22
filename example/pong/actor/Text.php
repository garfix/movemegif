<?php

namespace pong\actor;

use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;

/**
 * @author Patrick van Bergen
 */
class Text extends Actor
{
    const START_STEP = 240;
    const DURATION = 17;

    public function setupPosition()
    {
    }

    public function updatePosition($step)
    {
    }

    public function draw(GdCanvas $canvas, ClippingArea $clippingArea, $step)
    {
        if ($this->isActive($step)) {

            $offset = $step - self::START_STEP;
            $colorPart = (($offset + 1) / (self::DURATION)) * 0x40;

            $color = imagecolorallocate($canvas->getResource(), 0xc0 + $colorPart, 0xc0 + $colorPart, 0xff);

            $pixelSize = (10 + 2 * $offset * $offset);

            $letterWidth = $pixelSize * 3;
            $letterSpacing = $pixelSize;
            $letterHeight = $pixelSize * 5;

            $x = 250;
            $y = 125;

            $pX = $x - 1.5 * $letterSpacing - 2 * $letterWidth;
            $oX = $x - 0.5 * $letterSpacing - $letterWidth;
            $nX = $x + 0.5 * $letterSpacing;
            $gX = $x + 1.5 * $letterSpacing + $letterWidth;

            $pongY = $y - 0.5 * $letterHeight;

            $this->drawLetter($canvas, 'P', $pX, $pongY, $pixelSize, $color);
            $this->drawLetter($canvas, 'O', $oX, $pongY, $pixelSize, $color);
            $this->drawLetter($canvas, 'N', $nX, $pongY, $pixelSize, $color);
            $this->drawLetter($canvas, 'G', $gX, $pongY, $pixelSize, $color);

            $clippingArea->includePoint($pX, $pongY);
            $clippingArea->includePoint($pX + 4 * $letterWidth + 3 * $letterSpacing - 1, $pongY + $letterHeight - 1);
        }
    }

    public function isActive($step)
    {
        return ($step >= self::START_STEP && $step < self::START_STEP + self::DURATION);
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
        );

        return $font[$letter];
    }

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
}