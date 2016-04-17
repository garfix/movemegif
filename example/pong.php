<?php

use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;
use movemegif\GifBuilder;

// include movemegif's namespace
require_once __DIR__ . '/../php/autoloader.php';

// just for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(100);

class Pong
{
    const CANVAS_WIDTH = 500;
    const CANVAS_HEIGHT = 250;

    const BALL_SIZE = 10;
    const TRAIL_SIZE = 5;

    const CLIP_BALL = 'ball';
    const CLIP_PAD_LEFT = 'padLeft';
    const CLIP_PAD_RIGHT = 'padRight';

    const FRAMES_PER_STEP = 4;

    /** @var  GdCanvas */
    private $background;

    /** @var array|null  */
    private $ballPositions = null;

    /** @var array|null  */
    private $leftPadPositions = null;

    /** @var array|null  */
    private $rightPadPositions = null;

    /** @var ClippingArea[] */
    private $activeClippings = array();

    /** @var ClippingArea[] */
    private $previousClippings = array();

    public function __construct()
    {
        $this->createBackground();
        $this->setupPositions();
        $this->setupClippingAreas();
    }

    /**
     * @return GifBuilder
     */
    public function getBuilder()
    {
        $builder = new GifBuilder(self::CANVAS_WIDTH, self::CANVAS_HEIGHT);
        $builder->setRepeat();

        $frame = 0;
        while ($frame < 100) {

            $canvas = new GdCanvas(self::CANVAS_WIDTH, self::CANVAS_HEIGHT);

            $stepOffset = $frame % self::FRAMES_PER_STEP;
            $step = ($frame - $stepOffset) / self::FRAMES_PER_STEP;

            if ($stepOffset == 0) {
                $this->updatePositions($step);
            }

            $this->drawFrame($canvas, $step);

            switch ($stepOffset) {

                case 0:
                    $clippingArea = self::CLIP_PAD_LEFT;
                    $duration = 1;
                    break;

                case 1:
                    $clippingArea = self::CLIP_PAD_RIGHT;
                    $duration = 1;
                    break;

                case 2:
                    $clippingArea = self::CLIP_BALL;
                    $duration = 2;
                    break;

                default:
                    $clippingArea = null;
                    $duration = 0;

            }

            if ($duration) {

                if ($frame == 0) {
                    // render first frame in full
                    $clip = null;
                } else {
                    // the area that needs to be redrawn consists of new pixels that need to be added and old pixels that need to be removed
                    $clip = $this->activeClippings[$clippingArea]->getUnion($this->previousClippings[$clippingArea]);
                }

                // the area that is now active will be needed next time
                $this->previousClippings[$clippingArea] = $this->activeClippings[$clippingArea];

                $builder->addFrame()
                    ->setCanvas($canvas)
                    ->setClip($clip)
                    ->setDuration($duration);

            }

            $frame++;
        }

        return $builder;
    }

    private function createBackground()
    {
        $this->background = new GdCanvas(self::CANVAS_WIDTH, self::CANVAS_HEIGHT);

        $black = imagecolorallocate($this->background->getResource(), 0x00, 0x00, 0x00);
        $grey = imagecolorallocate($this->background->getResource(), 0x40, 0x40, 0x40);
        $white = imagecolorallocate($this->background->getResource(), 0xff, 0xff, 0xff);

        for ($i = 0; $i < 10; $i++) {
            imagefilledrectangle($this->background->getResource(), $i * 50, 0, $i * 50 + 25, self::CANVAS_HEIGHT, $black);
            imagefilledrectangle($this->background->getResource(), $i * 50 + 25, 0, $i * 50 + 50, self::CANVAS_HEIGHT, $grey);
        }

        imagefilledrectangle($this->background->getResource(), 10, 10, 489, 19, $white);
        imagefilledrectangle($this->background->getResource(), 10, 230, 489, 239, $white);

        for ($i = 0; $i < 11; $i++) {
            imagefilledrectangle($this->background->getResource(), 245, 20 + $i * 20, 254, 20 + $i * 20 + 9, $white);
        }
    }

    private function drawFrame(GdCanvas $canvas, $step)
    {
        $this->addBackground($canvas);
        $this->addPadLeftFrame($canvas);
        $this->addPadRightFrame($canvas);
        $this->addBallFrame($canvas);
    }

    private function addBackground(GdCanvas $canvas)
    {
        imagecopy($canvas->getResource(), $this->background->getResource(), 0, 0, 0, 0, self::CANVAS_WIDTH, self::CANVAS_HEIGHT);
    }

    private function addBallFrame(GdCanvas $canvas)
    {
        $ballColors = array();
        for ($b = 0; $b < self::TRAIL_SIZE; $b++) {
            $fract = ($b + 1) / self::TRAIL_SIZE;
            $ballColors[$b] = imagecolorallocate($canvas->getResource(), (int)($fract * 0x80), (int)($fract * 0xff), (int)($fract * 0x80));
        }

        $clip = new ClippingArea();

        for ($b = 0; $b < self::TRAIL_SIZE; $b++) {

            $x1 = $this->ballPositions[$b][0];
            $y1 = $this->ballPositions[$b][1];
            $x2 = $x1 + self::BALL_SIZE - 1;
            $y2 = $y1 + self::BALL_SIZE - 1;

            imagefilledrectangle($canvas->getResource(), $x1, $y1, $x2, $y2, $ballColors[$b]);

            $clip->includePoint($x1, $y1)->includePoint($x2, $y2);
        }

        // adjust clipping area
        $this->activeClippings[self::CLIP_BALL] = $clip;
    }

    private function addPadLeftFrame(GdCanvas $canvas)
    {
        $padColors = array();
        for ($b = 0; $b < self::TRAIL_SIZE; $b++) {
            $fract = ($b + 1) / self::TRAIL_SIZE;
            $padColors[$b] = imagecolorallocate($canvas->getResource(), (int)($fract * 0xf0), (int)($fract * 0x80), (int)($fract * 0x80));
        }

        $clip = new ClippingArea();

        for ($b = 0; $b < self::TRAIL_SIZE; $b++) {

            $x1 = $this->leftPadPositions[$b][0];
            $y1 = $this->leftPadPositions[$b][1];
            $x2 = $x1 + 9;
            $y2 = $y1 + 29;

            imagefilledrectangle($canvas->getResource(), $x1, $y1, $x2, $y2, $padColors[$b]);

            $clip->includePoint($x1, $y1)->includePoint($x2, $y2);
        }

        $this->activeClippings[self::CLIP_PAD_LEFT] = $clip;
    }

    private function addPadRightFrame(GdCanvas $canvas)
    {
        $padColors = array();
        for ($b = 0; $b < self::TRAIL_SIZE; $b++) {
            $fract = ($b + 1) / self::TRAIL_SIZE;
            $padColors[$b] = imagecolorallocate($canvas->getResource(), (int)($fract * 0xff), (int)($fract * 0x80), (int)($fract * 0x80));
        }

        $clip = new ClippingArea();

        for ($b = 0; $b < self::TRAIL_SIZE; $b++) {

            $x1 = $this->rightPadPositions[$b][0];
            $y1 = $this->rightPadPositions[$b][1];
            $x2 = $x1 + 9;
            $y2 = $y1 + 29;

            imagefilledrectangle($canvas->getResource(), $x1, $y1, $x2, $y2, $padColors[$b]);

            $clip->includePoint($x1, $y1)->includePoint($x2, $y2);
        }

        $this->activeClippings[self::CLIP_PAD_RIGHT] = $clip;
    }

    private function setupPositions()
    {
        if (!$this->ballPositions) {
            $this->ballPositions = array_fill(0, self::TRAIL_SIZE, array(20, 20));
        }

        if (!$this->leftPadPositions) {
            $this->leftPadPositions = array_fill(0, self::TRAIL_SIZE, array(10, 20));
        }

        if (!$this->rightPadPositions) {
            $this->rightPadPositions = array_fill(0, self::TRAIL_SIZE, array(480, 20));
        }
    }

    private function updatePositions($step)
    {
        $this->updateBallPosition($step);
        $this->updatePadLeft($step);
        $this->updatePadRight($step);
    }

    private function updateBallPosition($step)
    {
        // remove first position
        array_shift($this->ballPositions);
        // copy first position
        $position = $this->ballPositions[0];

        if ($step < 100) {
            $position[0] = 20 + $step * 10;;
            $position[1] = 20 + $step * 2;
        }

        // add it
        $this->ballPositions[] = $position;
    }

    private function updatePadLeft($step)
    {
        // remove first position
        array_shift($this->leftPadPositions);
        // copy first position
        $position = $this->leftPadPositions[0];

        if ($step < 10) {
            $position[1] = 20 + $step * 10;
        }

        // add it
        $this->leftPadPositions[] = $position;
    }

    private function updatePadRight($step)
    {
        // remove first position
        array_shift($this->rightPadPositions);
        // copy first position
        $position = $this->rightPadPositions[0];

        if ($step < 10) {
            $position[1] = 20 + $step * 10;
        }

        // add it
        $this->rightPadPositions[] = $position;

    }

    private function setupClippingAreas()
    {
        $this->activeClippings = array(
            self::CLIP_PAD_LEFT => new ClippingArea(),
            self::CLIP_PAD_RIGHT => new ClippingArea(),
            self::CLIP_BALL => new ClippingArea()
        );

        $this->previousClippings = $this->activeClippings;
    }
}

$pong = new Pong();
$builder = $pong->getBuilder();
$builder->output();

