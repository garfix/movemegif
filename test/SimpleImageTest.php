<?php

use movemegif\GifBuilder;

/**
 * @author Patrick van Bergen
 */
class SimpleImageTest extends PHPUnit_Framework_TestCase
{
    public function testCreateImage()
    {
        require_once __DIR__ . '/../php/autoloader.php';

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

        $colorTable = array(
            '0' => 0xFFFFFF,
            '1' => 0xFF0000,
            '2' => 0x0000FF,
            '3' => 0x000000
        );

        $Builder = new GifBuilder();

        $Builder->addImage()->setPixelsAndColors($pixelIndexes, $colorTable)->setUseLocalColorTable(false);

        $contents = $Builder->getContents();

        $actual = '';
        for ($i = 0; $i < strlen($contents); $i++) {
            $actual .= bin2hex($contents[$i]) . ' ';
        }
        $actual = strtoupper($actual);

        $expected = "47 49 46 38 39 61 0A 00 0A 00 91 00 00 FF FF FF FF 00 00 00 00 FF 00 00 00 21 F9 04 00 00 00 00 00 2C 00 00 00 00 0A 00 0A 00 00 02 16 8C 2D 99 87 2A 1C DC 33 A0 02 75 EC 95 FA A8 DE 60 8C 04 91 4C 01 00 3B ";

        $this->assertEquals($expected, $actual);
    }
}