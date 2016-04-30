<?php

namespace movemegif\exception;

use Exception;

/**
 * @author Patrick van Bergen
 */
class GifParseException extends Exception
{
    public static function header()
    {
        return new self('The image has no GIF89a header');
    }

    public static function applicationExtension()
    {
        return new self('Application extension not recognized');
    }
}