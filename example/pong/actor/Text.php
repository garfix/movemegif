<?php

namespace pong\actor;

use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;
use pong\lib\Font;
use pong\Pong;

/**
 * @author Patrick van Bergen
 */
class Text extends Actor
{
    const START_STEP = 240;
    const DURATION = 17;

    const HIGHLIGHT_DURATION = 5;

    public function setupPosition()
    {
    }

    public function updatePosition($step)
    {
    }

    public function draw(GdCanvas $canvas, ClippingArea $clippingArea, $step)
    {
        $font = new Font();

        $this->drawScore($canvas, $clippingArea, $font, $step, 0);
        $this->drawScore($canvas, $clippingArea, $font, $step, 1);

        if ($step >= self::START_STEP && $step < self::START_STEP + self::DURATION) {
            $this->drawPongText($canvas, $clippingArea, $font, $step);
        }
    }

    private function drawScore(GdCanvas $canvas, ClippingArea $clippingArea, Font $font, $step, $player)
    {
        $score = (int)floor(($step + ($player * 45)) / 90);

        // number of steps since last score
        $normalizedStep = $step % 90;
        if ($player == 1) {
            $normalizedStep -= 45;
        }

        $colorFract = 1;
        // exclude the start
        if ($step > $normalizedStep) {
            // flash the new score
            if ($normalizedStep >= 0 && $normalizedStep < self::HIGHLIGHT_DURATION) {
                $colorFract = ($normalizedStep + 1) / self::HIGHLIGHT_DURATION;
            }
        }
        $color = imagecolorallocate($canvas->getResource(), 0xa0 + $colorFract * 0x5f, 0xa0 + $colorFract * 0x5f, 0xff);

        $y = 30;
        $dotWidth = 6;
        $x = Pong::CANVAS_WIDTH / 2 + ($player ? 10 + 0.5 * $dotWidth : -10 - 7.5 * $dotWidth);

        $font->drawLetter($canvas, '0', $x, $y, $dotWidth, $color);
        $font->drawLetter($canvas, (string)$score, $x + 4 * $dotWidth, $y, $dotWidth, $color);

        $clippingArea->includePoint($x, $y)->includePoint($x + 7 * $dotWidth - 1, $y + 5 * $dotWidth - 1);
    }

    private function drawPongText(GdCanvas $canvas, ClippingArea $clippingArea, Font $font, $step)
    {
        $offset = $step - self::START_STEP;
        $colorPart = (($offset + 1) / (self::DURATION)) * 0x3f;

        $color = imagecolorallocate($canvas->getResource(), 0xc0 + $colorPart, 0xc0 + $colorPart, 0xff);

        $pixelSize = (10 + 2 * $offset * $offset);

        $letterWidth = $pixelSize * 3;
        $letterSpacing = $pixelSize;
        $letterHeight = $pixelSize * 5;

        $x = Pong::CANVAS_WIDTH / 2;
        $y = Pong::CANVAS_HEIGHT / 2;

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

    public function isActive($step)
    {
        // score change
        if ($step % 45 < self::HIGHLIGHT_DURATION) {
            return true;
        }

        // PONG-text animation
        if ($step >= self::START_STEP && $step < self::START_STEP + self::DURATION) {
            return true;
        }

        return false;
    }
}