<?php

namespace movemegif\data;

use movemegif\domain\ClippingArea;
use movemegif\domain\Frame;

/**
 * @author Patrick van Bergen
 */
class Clipper
{
    public function getClip(Frame $frame, $imageWidth, $imageHeight)
    {
        $clippingArea = $frame->getClip();

        $frameArea = new ClippingArea(0, 0, $frame->getWidth() - 1, $frame->getHeight() - 1);

        $imageArea = new ClippingArea(0, 0, $imageWidth - 1, $imageHeight - 1);
        $imageArea = $imageArea->getTranslation(-$frame->getLeft(), -$frame->getTop());

        $clip = $clippingArea->getIntersection($frameArea)->getIntersection($imageArea);

        return $clip;
    }
}