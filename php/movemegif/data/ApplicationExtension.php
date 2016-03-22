<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class ApplicationExtension
{
    const EXTENSION_INTRODUCER = 0x21;
    const GRAPHIC_CONTROL_LABEL = 0xFF;
    const STRING_LENGTH = 11;
    const APP_START = 1;

    private $repeatCount = 0;

    public function getContents()
    {
        return
            chr(self::EXTENSION_INTRODUCER) . chr(self::GRAPHIC_CONTROL_LABEL) .
            chr(self::STRING_LENGTH) . 'NETSCAPE2.0' .
            chr(3) . chr(self::APP_START) . pack('v', $this->repeatCount) .
            chr(0);
    }
}