<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class Trailer
{
    public function getContents()
    {
        return chr(0x3B);
    }
}