<?php

namespace pong\actor;

use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;

/**
 * @author Patrick van Bergen
 */
class Ball extends Actor
{
    const BALL_SIZE = 10;
    const BALL_TRAIL_SIZE = 5;
    const BALL_MAX_RIGHT = 470;
    const BALL_MAX_LEFT = 20;
    const BALL_MAX_TOP = 20;
    const BALL_MAX_BOTTOM = 220;

    /** @var array|null  */
    private $ballPositions = null;

    private $ballSpeed = null;

    public function setupPosition()
    {
        $this->ballPositions = array_fill(0, self::BALL_TRAIL_SIZE, array(20, 124));
        $this->ballSpeed = array(10, 2.96);

        // start a trail before the first frame
        for ($i = 0; $i < self::BALL_TRAIL_SIZE; $i++) {
            $this->updatePosition(0);
        }
    }

    public function updatePosition($step)
    {
        // copy last position
        $position = $this->ballPositions[self::BALL_TRAIL_SIZE - 1];
        // remove first position
        array_shift($this->ballPositions);

        $position[0] += $this->ballSpeed[0];
        $position[1] += $this->ballSpeed[1];

        // bounce

        if ($position[0] > self::BALL_MAX_RIGHT) {
            $position[0] = self::BALL_MAX_RIGHT - ($position[0] - self::BALL_MAX_RIGHT);
            $this->ballSpeed[0] = -$this->ballSpeed[0];
        }

        if ($position[0] < self::BALL_MAX_LEFT) {
            $position[0] = self::BALL_MAX_LEFT + (self::BALL_MAX_LEFT - $position[0]);
            $this->ballSpeed[0] = -$this->ballSpeed[0];
        }

        if ($position[1] > self::BALL_MAX_BOTTOM) {
            $position[1] = self::BALL_MAX_BOTTOM - ($position[1] - self::BALL_MAX_BOTTOM);
            $this->ballSpeed[1] = -$this->ballSpeed[1];
        }

        if ($position[1] < self::BALL_MAX_TOP) {
            $position[1] = self::BALL_MAX_TOP + (self::BALL_MAX_TOP - $position[1]);
            $this->ballSpeed[1] = -$this->ballSpeed[1];
        }

        // add new position
        $this->ballPositions[] = $position;

    }

    public function draw(GdCanvas $canvas, ClippingArea $clippingArea, $step)
    {
        $ballColors = array();
        for ($b = 0; $b < self::BALL_TRAIL_SIZE; $b++) {
            $fract = ($b + 1) / self::BALL_TRAIL_SIZE;
            $ballColors[$b] = imagecolorallocate($canvas->getResource(), (int)($fract * 0x80), (int)($fract * 0xff), (int)($fract * 0x80));
        }

        for ($b = 0; $b < self::BALL_TRAIL_SIZE; $b++) {

            $x1 = (int)$this->ballPositions[$b][0];
            $y1 = (int)$this->ballPositions[$b][1];
            $x2 = $x1 + self::BALL_SIZE - 1;
            $y2 = $y1 + self::BALL_SIZE - 1;

            imagefilledrectangle($canvas->getResource(), $x1, $y1, $x2, $y2, $ballColors[$b]);

            $clippingArea->includePoint($x1, $y1)->includePoint($x2, $y2);
        }
    }

    public function getBallPosition()
    {
        return $this->ballPositions[self::BALL_TRAIL_SIZE - 1];
    }
}