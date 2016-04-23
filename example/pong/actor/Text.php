<?php

namespace pong\actor;

use Font;
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

            $font = new Font();

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

            $font->drawLetter($canvas, 'P', $pX, $pongY, $pixelSize, $color);
            $font->drawLetter($canvas, 'O', $oX, $pongY, $pixelSize, $color);
            $font->drawLetter($canvas, 'N', $nX, $pongY, $pixelSize, $color);
            $font->drawLetter($canvas, 'G', $gX, $pongY, $pixelSize, $color);

            $clippingArea->includePoint($pX, $pongY);
            $clippingArea->includePoint($pX + 4 * $letterWidth + 3 * $letterSpacing - 1, $pongY + $letterHeight - 1);
        }
    }

    public function isActive($step)
    {
        return ($step >= self::START_STEP && $step < self::START_STEP + self::DURATION);
    }
}