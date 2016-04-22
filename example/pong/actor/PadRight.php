<?php

namespace pong\actor;

use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;

/**
 * @author Patrick van Bergen
 */
class PadRight extends Pad
{
    /** @var array|null  */
    private $rightPadPositions = null;

    public function setupPosition()
    {
        $this->rightPadPositions = array_fill(0, self::PAD_TRAIL_SIZE, array(480, 20));

        // start a trail before the first frame
        for ($i = 0; $i < self::PAD_TRAIL_SIZE; $i++) {
            $this->updatePosition(0);
        }
    }

    public function updatePosition($step)
    {
        // remove first position
        array_shift($this->rightPadPositions);
        // copy first position
        $position = $this->rightPadPositions[0];

        $ballPosition = $this->ball->getBallPosition();

        $position[1] = max(min($ballPosition[1] - 10, self::PAD_MAX_BOTTOM), self::PAD_MAX_TOP);

        // add it
        $this->rightPadPositions[] = $position;
    }

    public function draw(GdCanvas $canvas, ClippingArea $clippingArea, $step)
    {
        $padColors = array();
        for ($b = 0; $b < self::PAD_TRAIL_SIZE; $b++) {
            $fract = ($b + 1) / self::PAD_TRAIL_SIZE;
            $padColors[$b] = imagecolorallocate($canvas->getResource(), (int)($fract * 0xff), (int)($fract * 0x80), (int)($fract * 0x80));
        }

        for ($b = 0; $b < self::PAD_TRAIL_SIZE; $b++) {

            $x1 = (int)$this->rightPadPositions[$b][0];
            $y1 = (int)$this->rightPadPositions[$b][1];
            $x2 = $x1 + 9;
            $y2 = $y1 + 29;

            imagefilledrectangle($canvas->getResource(), $x1, $y1, $x2, $y2, $padColors[$b]);

            $clippingArea->includePoint($x1, $y1)->includePoint($x2, $y2);
        }
    }
}