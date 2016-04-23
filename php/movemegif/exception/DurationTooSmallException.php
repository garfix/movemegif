<?php

namespace movemegif\exception;

/**
 * @author Patrick van Bergen
 */
class DurationTooSmallException extends MovemegifException
{
    /**
     * @return DurationTooSmallException
     */
    public static function create()
    {
        return new self('Duration values of 0 and 1 are punished by most browsers. Use values of 2 or higher.');
    }
}