<?php
use movemegif\data\Formatter;
use movemegif\domain\StringCanvas;
use movemegif\GifBuilder;

require_once __DIR__ . '/../../php/autoloader.php';

/**
 * Tests global color table shared by two frames.
 * Tests local color tables.
 * Tests a color table with more than 4 (8) entries.
 * Tests duration.
 * Integration test that builds complete GIF.
 *
 * @author Patrick van Bergen
 */
class ColorTableTest extends PHPUnit_Framework_TestCase
{
    public function testGlobalColorTable()
    {
        $indexString = "
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

        $builder = new GifBuilder(4, 4);

        $builder->addFrame()
            ->setCanvas(new StringCanvas(4, 4, $indexString, $index2color))
            ->setUseGlobalColorTable()
            ->setDuration(50);

        $indexString = "
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

        $builder->addFrame()
            ->setCanvas(new StringCanvas(4, 4, $indexString, $index2color))
            ->setUseGlobalColorTable()
            ->setDuration(50);

        $contents = $builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = "47 49 46 38 39 61 04 00 04 00 92 00 00 FF 00 00 FF FF FF 00 00 00 00 00 FF 80 80 80 00 00 00 00 00 00 00 00 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 04 00 04 00 00 02 07 04 12 20 82 7B 09 0A 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 04 00 04 00 00 03 08 38 43 34 22 EC AD 36 12 00 21 FE 16 43 72 65 61 74 65 64 20 77 69 74 68 20 6D 6F 76 65 6D 65 67 69 66 00 3B";

        $this->assertEquals($expected, $actual);
    }

    public function testLocalColorTables()
    {
        $indexString = "
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

        $builder = new GifBuilder(4, 4);

        $builder->addFrame()
            ->setCanvas(new StringCanvas(4, 4, $indexString, $index2color))
            ->setUseLocalColorTable()
            ->setDuration(50);

        $indexString = "
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

        $builder->addFrame()
            ->setCanvas(new StringCanvas(4, 4, $indexString, $index2color))
            ->setUseLocalColorTable()
            ->setDuration(50);

        $contents = $builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = "47 49 46 38 39 61 04 00 04 00 91 00 00 00 00 00 00 00 00 00 00 00 00 00 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 04 00 04 00 81 FF 00 00 FF FF FF 00 00 00 00 00 00 02 07 04 12 20 82 7B 09 0A 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 04 00 04 00 81 00 00 FF 80 80 80 00 00 00 00 00 00 02 07 04 12 20 82 7B 09 0A 00 21 FE 16 43 72 65 61 74 65 64 20 77 69 74 68 20 6D 6F 76 65 6D 65 67 69 66 00 3B";

        $this->assertEquals($expected, $actual);
    }
}