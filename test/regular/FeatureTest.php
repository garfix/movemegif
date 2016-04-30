<?php

use movemegif\data\Formatter;
use movemegif\domain\StringCanvas;
use movemegif\GifBuilder;

require_once __DIR__ . '/../../php/autoloader.php';

/**
 * Tests frames with top/left offset.
 * Tests palette with only 2 colors.
 * Tests duration.
 * Tests disposal methods.
 * Integration test that builds complete GIF.
 *
 * @author Patrick van Bergen
 */
class FeatureTest extends PHPUnit_Framework_TestCase
{
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

        $builder = new GifBuilder(6, 6);

        $canvas = new StringCanvas(6, 6, $frame1, $index2color);
        $canvas->setTransparencyColor(0xFF0000);

        $builder->addFrame()
            ->setCanvas($canvas)
            ->setUseGlobalColorTable()
            ->setDuration(50)
            ->setDisposalToOverwriteWithPreviousFrame()
        ;

        $frame2 = "
            2 2 2 2
            2 1 1 2
            2 2 2 2
        ";

        $builder->addFrame()->setLeft(2)->setTop(3)
            ->setCanvas(new StringCanvas(4, 3, $frame2, $index2color))
            ->setUseGlobalColorTable()
            ->setDisposalToOverwriteWithBackground()
        ;

        $contents = $builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = "47 49 46 38 39 61 06 00 06 00 91 00 00 FF FF FF FF 00 00 00 00 00 00 00 00 21 F9 04 0D 32 00 01 00 2C 00 00 00 00 06 00 06 00 00 02 0A 84 11 71 A8 97 B9 A0 6B A6 00 00 21 F9 04 08 00 00 00 00 2C 02 00 03 00 04 00 03 00 00 02 04 8C 0D 70 56 00 21 FE 16 43 72 65 61 74 65 64 20 77 69 74 68 20 6D 6F 76 65 6D 65 67 69 66 00 3B";

        $this->assertEquals($expected, $actual);
    }
}