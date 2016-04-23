<?php

namespace movemegif\exception;

use Exception;

/**
 * @author Patrick van Bergen
 */
class EmptyFrameException extends MovemegifException
{
    /**
     * @return EmptyFrameException
     */
    public static function create()
    {
        return new self("This frame contains no visible pixels. It cannot be used as a GIF frame.");
    }
}