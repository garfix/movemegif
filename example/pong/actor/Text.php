<?php

namespace pong\actor;

use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;

/**
 * @author Patrick van Bergen
 */
class Text extends Actor
{
    public function setupPosition()
    {
    }

    public function updatePosition($step)
    {
    }

    public function draw(GdCanvas $canvas, ClippingArea $clippingArea)
    {
        $color = imagecolorallocate($canvas->getResource(), 0xff, 0xff, 0xff);
$step = 1;
        if ($step >= 1 and $step < 10) {

//            imagestring($canvas->getResource(), 5, 10, 30, "PONG", $color);

            $clippingArea->includePoint(10, 30)->includePoint(100, 50);
        }
    }
}