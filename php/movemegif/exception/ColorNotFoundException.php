<?php

namespace movemegif\exception;

/**
 * @author Patrick van Bergen
 */
class ColorNotFoundException extends MovemegifException
{
    /**
     * @return ColorNotFoundException
     */
    public static function create($index)
    {
        return new self('The map contains no color for index ' . $index);
    }

}