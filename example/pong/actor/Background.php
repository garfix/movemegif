<?php

namespace pong\actor;

use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;
use pong\Pong;

/**
 * @author Patrick van Bergen
 */
class Background extends Actor
{
    /** @var  GdCanvas */
    private $background;

    public function setupPosition()
    {
        $this->background = new GdCanvas(Pong::CANVAS_WIDTH, Pong::CANVAS_HEIGHT);

        $black = imagecolorallocate($this->background->getResource(), 0x00, 0x00, 0x00);
        $grey = imagecolorallocate($this->background->getResource(), 0x40, 0x40, 0x40);
        $white = imagecolorallocate($this->background->getResource(), 0xff, 0xff, 0xff);

        for ($i = 0; $i < 10; $i++) {
            imagefilledrectangle($this->background->getResource(), $i * 50, 0, $i * 50 + 25, Pong::CANVAS_HEIGHT, $black);
            imagefilledrectangle($this->background->getResource(), $i * 50 + 25, 0, $i * 50 + 50, Pong::CANVAS_HEIGHT, $grey);
        }

        imagefilledrectangle($this->background->getResource(), 10, 10, 489, 19, $white);
        imagefilledrectangle($this->background->getResource(), 10, 230, 489, 239, $white);

        for ($i = 0; $i < 11; $i++) {
            imagefilledrectangle($this->background->getResource(), 245, 20 + $i * 20, 254, 20 + $i * 20 + 9, $white);
        }
    }

    public function updatePosition($step)
    {
    }

    public function draw(GdCanvas $canvas, ClippingArea $clippingArea, $step)
    {
        imagecopy($canvas->getResource(), $this->background->getResource(), 0, 0, 0, 0, Pong::CANVAS_WIDTH, Pong::CANVAS_HEIGHT);
    }
}