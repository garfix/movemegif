<?php

use movemegif\data\Formatter;
use movemegif\GifBuilder;

require_once __DIR__ . '/../php/autoloader.php';

/**
 * @author Patrick van Bergen
 */
class FrameOffsetTest extends PHPUnit_Framework_TestCase
{
    // also tests: palette with only 2 colors
    public function testOffset()
    {
        $frame1 = "
            1 1 1 1 2 2
            1 1 1 1 2 2
            1 1 1 1 2 2
            2 2 1 1 1 1
            2 2 1 1 1 1
            2 2 1 1 1 1
        ";

        $index2color = array(
            '1' => 0xFFFFFF,
            '2' => 0xFF0000,
        );

        $Builder = new GifBuilder(6, 6);
        $Builder->addFrame(6, 6)->setPixelsAndColors($frame1, $index2color)->setDuration(50);

        $frame2 = "
            2 2 2 2
            2 1 1 2
            2 2 2 2
        ";

        $Builder->addFrame(4, 3, 2, 3)->setPixelsAndColors($frame2, $index2color);

        $contents = $Builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = "47 49 46 38 39 61 06 00 06 00 91 00 00 FF FF FF FF 00 00 00 00 00 00 00 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 06 00 06 00 00 02 0A 84 11 71 A8 97 B9 A0 6B A6 00 00 21 F9 04 00 00 00 00 00 2C 02 00 03 00 04 00 03 00 00 02 04 8C 0D 70 56 00 3B";

        $this->assertEquals($expected, $actual);
    }
}