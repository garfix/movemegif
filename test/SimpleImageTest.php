<?php

use movemegif\data\Formatter;
use movemegif\GifBuilder;

require_once __DIR__ . '/../php/autoloader.php';

/**
 * @author Patrick van Bergen
 */
class SimpleImageTest extends PHPUnit_Framework_TestCase
{
    public function testCreateImage()
    {
        $pixelIndexes = "
            1 1 1 1 1 2 2 2 2 2
            1 1 1 1 1 2 2 2 2 2
            1 1 1 1 1 2 2 2 2 2
            1 1 1 0 0 0 0 2 2 2
            1 1 1 0 0 0 0 2 2 2
            2 2 2 0 0 0 0 1 1 1
            2 2 2 0 0 0 0 1 1 1
            2 2 2 2 2 1 1 1 1 1
            2 2 2 2 2 1 1 1 1 1
            2 2 2 2 2 1 1 1 1 1
        ";

        $index2color = array(
            '0' => 0xFFFFFF,
            '1' => 0xFF0000,
            '2' => 0x0000FF,
            '3' => 0x000000
        );

        $Builder = new GifBuilder(10, 10);
        $Builder->addFrame(10, 10)->setPixelsAndColors($pixelIndexes, $index2color)->setUseLocalColorTable(false);

        $contents = $Builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = "47 49 46 38 39 61 0A 00 0A 00 91 00 00 FF 00 00 00 00 FF FF FF FF 00 00 00 21 F9 04 00 00 00 00 00 2C 00 00 00 00 0A 00 0A 00 00 02 16 84 1D 99 87 1A 0C DC 33 A2 0A 75 EC 95 FA A8 DE 60 8C 04 91 4C 01 00 3B";

        $this->assertEquals($expected, $actual);
    }
}