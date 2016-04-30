<?php

use movemegif\data\Formatter;
use movemegif\domain\StringCanvas;
use movemegif\GifBuilder;

require_once __DIR__ . '/../../php/autoloader.php';

/**
 * Integration test that builds a simple GIF.
 * Tests defaults.
 *
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

        $canvas = new StringCanvas(10, 10, $pixelIndexes, $index2color);

        $builder = new GifBuilder(10, 10);
        $builder->addFrame()->setCanvas($canvas)->setUseGlobalColorTable();

        $contents = $builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = "47 49 46 38 39 61 0A 00 0A 00 91 00 00 FF 00 00 00 00 FF FF FF FF 00 00 00 21 F9 04 00 00 00 00 00 2C 00 00 00 00 0A 00 0A 00 00 02 16 84 1D 99 87 1A 0C DC 33 A2 0A 75 EC 95 FA A8 DE 60 8C 04 91 4C 01 00 21 FE 16 43 72 65 61 74 65 64 20 77 69 74 68 20 6D 6F 76 65 6D 65 67 69 66 00 3B";

        $this->assertEquals($expected, $actual);
    }
}