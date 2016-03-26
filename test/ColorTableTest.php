<?php
use movemegif\data\Formatter;
use movemegif\GifBuilder;

require_once __DIR__ . '/../php/autoloader.php';

/**
 * @author Patrick van Bergen
 */
class ColorTableTest extends PHPUnit_Framework_TestCase
{
    public function testGlobalColorTable()
    {
        $pixelIndexes = "
            1 1 2 2
            1 3 3 2
            2 3 3 1
            2 2 1 1
        ";

        $index2color = array(
            '1' => 0xFF0000,
            '2' => 0xFFFFFF,
            '3' => 0x000000,
        );

        $Builder = new GifBuilder(4, 4);

        $Builder->addFrame(4, 4)
            ->setPixelsAndColors($pixelIndexes, $index2color)
            ->setUseLocalColorTable(false)
            ->setDuration(50);

        $pixelIndexes = "
            4 4 5 5
            4 3 3 5
            5 3 3 4
            5 5 4 4
        ";

        $index2color = array(
            '3' => 0x000000,
            '4' => 0x0000FF,
            '5' => 0x808080,
        );

        $Builder->addFrame(4, 4)
            ->setPixelsAndColors($pixelIndexes, $index2color)
            ->setUseLocalColorTable(false)
            ->setDuration(50);

        $contents = $Builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = "47 49 46 38 39 61 04 00 04 00 92 00 00 FF 00 00 FF FF FF 00 00 00 00 00 FF 80 80 80 00 00 00 00 00 00 00 00 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 04 00 04 00 00 02 07 04 12 20 82 7B 09 0A 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 04 00 04 00 00 03 08 38 43 34 22 EC AD 36 12 00 3B";

        $this->assertEquals($expected, $actual);
    }

    public function testLocalColorTables()
    {
        $pixelIndexes = "
            1 1 2 2
            1 3 3 2
            2 3 3 1
            2 2 1 1
        ";

        $colorTable = array(
            '1' => 0xFF0000,
            '2' => 0xFFFFFF,
            '3' => 0x000000,
        );

        $Builder = new GifBuilder(4, 4);

        $Builder->addFrame(4, 4)
            ->setPixelsAndColors($pixelIndexes, $colorTable)
            ->setUseLocalColorTable(true)
            ->setDuration(50);

        $pixelIndexes = "
            4 4 5 5
            4 3 3 5
            5 3 3 4
            5 5 4 4
        ";

        $colorTable = array(
            '3' => 0x000000,
            '4' => 0x0000FF,
            '5' => 0x808080,
        );

        $Builder->addFrame(4, 4)
            ->setPixelsAndColors($pixelIndexes, $colorTable)
            ->setUseLocalColorTable(true)
            ->setDuration(50);

        $contents = $Builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = "47 49 46 38 39 61 04 00 04 00 91 00 00 00 00 00 00 00 00 00 00 00 00 00 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 04 00 04 00 81 FF 00 00 FF FF FF 00 00 00 00 00 00 02 07 04 12 20 82 7B 09 0A 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 04 00 04 00 81 00 00 FF 80 80 80 00 00 00 00 00 00 02 07 04 12 20 82 7B 09 0A 00 3B";

        $this->assertEquals($expected, $actual);
    }
}