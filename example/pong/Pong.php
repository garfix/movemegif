<?php

namespace pong;

use pong\actor\Actor;
use pong\actor\Background;
use pong\actor\Ball;
use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;
use movemegif\GifBuilder;
use pong\actor\PadLeft;
use pong\actor\PadRight;
use pong\actor\Text;

class Pong
{
    const CANVAS_WIDTH = 500;
    const CANVAS_HEIGHT = 250;

    const FRAMES_PER_STEP = 4;

    const BACKGROUND = 'background';
    const BALL = 'ball';
    const PAD_LEFT = 'padLeft';
    const PAD_RIGHT = 'padRight';
    const TEXT = 'text';

    /** @var Actor[] */
    private $actors = array();

    public function __construct()
    {
        $ball = new Ball();

        $this->actors[self::BACKGROUND] = new Background();
        $this->actors[self::BALL] = $ball;
        $this->actors[self::PAD_LEFT] = new PadLeft($ball);
        $this->actors[self::PAD_RIGHT] = new PadRight($ball);
        $this->actors[self::TEXT] = new Text();

        $this->setupPositions();
    }

    /**
     * @return GifBuilder
     */
    public function getBuilder()
    {
        $builder = new GifBuilder(self::CANVAS_WIDTH, self::CANVAS_HEIGHT);
        $builder->setRepeat();

        $duration = 0;
        $actor = null;
        $frame = 0;

        do {

            $canvas = new GdCanvas(self::CANVAS_WIDTH, self::CANVAS_HEIGHT);

            $stepOffset = $frame % self::FRAMES_PER_STEP;
            $step = ($frame - $stepOffset) / self::FRAMES_PER_STEP;

            if ($stepOffset == 0) {
                $this->updatePositions($step);
            }

            $this->drawFrame($canvas, $step);

            switch ($stepOffset) {

                case 0:
                    $actor = self::PAD_LEFT;
                    $duration = 1;
                    break;

                case 1:
                    $actor = self::PAD_RIGHT;
                    $duration = 1;
                    break;

                case 2:
                    $actor = self::BALL;
//                    $duration = $this->textIsShowing($step) ? 1 : 2;
                    $duration = 2;
                    break;

                case 3:
                    $actor = self::TEXT;
//                    $duration = $this->textIsShowing($step) ? 1 : 0;
                    $duration = 0;

            }

            if ($duration) {

                if ($frame == 0) {
                    // render first frame in full
                    $clip = null;
                } else {
                    // the area that needs to be redrawn consists of new pixels that need to be added and old pixels that need to be removed
                    $clip = $this->actors[$actor]->getActiveClippingArea()->getUnion(
                        $this->actors[$actor]->getPreviousClippingArea());
                }

                // the area that is now active will be needed next time
                $this->actors[$actor]->setPreviousClippingArea(
                    $this->actors[$actor]->getActiveClippingArea());

                $builder->addFrame()
                    ->setCanvas($canvas)
                    ->setClip($clip)
                    ->setDuration($duration);

            }

            $frame++;

            // number of steps is finetuned to make the animation loop properly
        } while ($step < 270);
//        } while ($step < 30);

        return $builder;
    }

    private function setupPositions()
    {
        foreach ($this->actors as $actor) {
            $actor->setupPosition();
        }
    }

    private function drawFrame(GdCanvas $canvas, $step)
    {
        foreach ($this->actors as $actor) {
            $clippingArea = new ClippingArea();
            $actor->draw($canvas, $clippingArea);
            $actor->setActiveClippingArea($clippingArea);
        }
    }

    private function updatePositions($step)
    {
        foreach ($this->actors as $actor) {
            $actor->updatePosition($step);
        }
    }
}
