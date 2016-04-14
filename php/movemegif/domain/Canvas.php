<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
interface Canvas
{
    public function getWidth();

    public function getHeight();

    public function getPixels($clipLeft, $clipTop, $clipRight, $clipBottom);
}