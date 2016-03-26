<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class Repeat
{
    private $count = 0;

    public function forever()
    {
        $this->count = 0;
    }

    public function setTimes($n)
    {
#todo max = 65535
        $this->count = $n;
    }

    public function getTimes()
    {
        return $this->count;
    }
}