<?php

namespace pong\actor;

/**
 * @author Patrick van Bergen
 */
class Pad extends Actor
{
    const PAD_TRAIL_SIZE = 5;
    const PAD_MAX_TOP = 20;
    const PAD_MAX_BOTTOM = 200;

    /** @var  Ball */
    protected $ball;

    public function __construct(Ball $ball)
    {
        parent::__construct();

        $this->ball = $ball;
    }
}