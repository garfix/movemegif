<?php

namespace movemegif\exception;

/**
 * @author Patrick van Bergen
 */
class TooManyColorsException extends MovemegifException
{
    public static function create()
    {
        return new self("The image contains more than 256 colors. This is more than GIF can handle.");
    }
}