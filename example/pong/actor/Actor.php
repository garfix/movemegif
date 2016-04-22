<?php

namespace pong\actor;

use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;

/**
 * @author Patrick van Bergen
 */
class Actor
{
    /** @var  ClippingArea */
    private $previousClippingArea;

    /** @var  ClippingArea */
    private $activeClippingArea;

    public function __construct()
    {
        $this->activeClippingArea = new ClippingArea();
        $this->previousClippingArea = $this->activeClippingArea;
    }

    public function setupPosition()
    {
    }

    public function updatePosition($step)
    {
    }

    public function draw(GdCanvas $canvas, ClippingArea $clippingArea, $step)
    {
    }

    public function setActiveClippingArea(ClippingArea $clippingArea)
    {
        $this->activeClippingArea = $clippingArea;
    }

    public function getActiveClippingArea()
    {
        return $this->activeClippingArea;
    }

    public function setPreviousClippingArea(ClippingArea $clippingArea)
    {
        $this->previousClippingArea = $clippingArea;
    }

    public function getPreviousClippingArea()
    {
        return $this->previousClippingArea;
    }
}