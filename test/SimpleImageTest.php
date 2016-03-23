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

        $Builder = new GifBuilder();

        $Builder->addImage();

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