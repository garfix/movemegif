<?php

namespace movemegif\exception;

/**
 * @author Patrick van Bergen
 */
class InvalidDimensionsException extends MovemegifException
{
    /**
     * @return InvalidDimensionsException
     */
    public static function create()
    {
        return new self("The number of pixels in the indexString does not match the width and height.");
    }
}