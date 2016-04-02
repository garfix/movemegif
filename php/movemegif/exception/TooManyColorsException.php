<?php

namespace movemegif\exception;

/**
 * @author Patrick van Bergen
 */
class TooManyColorsException extends MovemegifException
{
    public static function create($isLocalColorTable)
    {
        if ($isLocalColorTable) {
            return new self("The local color table overflows. It cannot contain more than 256 colors. Convert your frame's image to a simpler palette.");
        } else {
            return new self("The global color table overflows. It cannot contain more than 256 colors. Call 'setUseLocalColorTable()' on each frame.");
        }
    }
}