<?php

use movemegif\data\Clipper;
use movemegif\data\Formatter;
use movemegif\domain\ClippingArea;
use movemegif\domain\Frame;
use movemegif\domain\GdCanvas;
use movemegif\domain\StringCanvas;

require_once __DIR__ . '/../../php/autoloader.php';

/**
 * @author Patrick van Bergen
 */
class ClipTest extends PHPUnit_Framework_TestCase
{
    public function testClipGdCanvas()
    {
        $builder = new \movemegif\GifBuilder(10, 10);

        // red background
        $canvas = new GdCanvas(10, 10);
        $red = imagecolorallocate($canvas->getResource(), 0xff, 0x00, 0x00);
        imagefilledrectangle($canvas->getResource(), 0, 0, 10, 10, $red);

        $builder->addFrame()->setCanvas($canvas)->setUseGlobalColorTable();

        // green/blue rectangle
        $canvas = new GdCanvas(6, 6);
        $green = imagecolorallocate($canvas->getResource(), 0x00, 0xff, 0x00);
        $blue = imagecolorallocate($canvas->getResource(), 0x00, 0x00, 0xff);
        imagefilledrectangle($canvas->getResource(), 0, 0, 6, 3, $green);
        imagefilledrectangle($canvas->getResource(), 0, 3, 6, 6, $blue);

        $clip = new ClippingArea();
        $clip->includePoint(1, 1)->includePoint(4, 4);
        $builder->addFrame()->setCanvas($canvas)->setTop(2)->setLeft(2)->setClip($clip)->setUseGlobalColorTable();

        $contents = $builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = '47 49 46 38 39 61 0A 00 0A 00 91 00 00 FF 00 00 00 FF 00 00 00 FF 00 00 00 21 F9 04 00 00 00 00 00 2C 00 00 00 00 0A 00 0A 00 00 02 08 84 8F A9 CB ED 0F 63 2B 00 21 F9 04 00 00 00 00 00 2C 03 00 03 00 04 00 04 00 00 02 05 8C 6F A2 AB 05 00 21 FE 16 43 72 65 61 74 65 64 20 77 69 74 68 20 6D 6F 76 65 6D 65 67 69 66 00 3B';

        $this->assertEquals($expected, $actual);
    }

    public function testClipStringCanvas()
    {
        $builder = new \movemegif\GifBuilder(10, 10);

        // red background
        $indexString = "
            1 1 1 1 1 1 1 1 1 1
            1 1 1 1 1 1 1 1 1 1
            1 1 1 1 1 1 1 1 1 1
            1 1 1 1 1 1 1 1 1 1
            1 1 1 1 1 1 1 1 1 1
            1 1 1 1 1 1 1 1 1 1
            1 1 1 1 1 1 1 1 1 1
            1 1 1 1 1 1 1 1 1 1
            1 1 1 1 1 1 1 1 1 1
            1 1 1 1 1 1 1 1 1 1
        ";
        $index2color = array(
            '1' => 0xff0000
        );
        $canvas = new StringCanvas(10, 10, $indexString, $index2color);

        $builder->addFrame()->setCanvas($canvas)->setUseGlobalColorTable();

        // green/blue rectangle
        $indexString = "
            1 1 1 1 1 1
            1 1 1 1 1 1
            1 1 1 1 1 1
            2 2 2 2 2 2
            2 2 2 2 2 2
            2 2 2 2 2 2
        ";
        $index2color = array(
            '1' => 0x00ff00,
            '2' => 0x0000ff,
        );
        $canvas = new StringCanvas(6, 6, $indexString, $index2color);

        $clip = new ClippingArea();
        $clip->includePoint(1, 1)->includePoint(4, 4);
        $builder->addFrame()->setCanvas($canvas)->setTop(2)->setLeft(2)->setClip($clip)->setUseGlobalColorTable();

        $contents = $builder->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = '47 49 46 38 39 61 0A 00 0A 00 91 00 00 FF 00 00 00 FF 00 00 00 FF 00 00 00 21 F9 04 00 00 00 00 00 2C 00 00 00 00 0A 00 0A 00 00 02 08 84 8F A9 CB ED 0F 63 2B 00 21 F9 04 00 00 00 00 00 2C 03 00 03 00 04 00 04 00 00 02 05 8C 6F A2 AB 05 00 21 FE 16 43 72 65 61 74 65 64 20 77 69 74 68 20 6D 6F 76 65 6D 65 67 69 66 00 3B';

        $this->assertEquals($expected, $actual);
    }

    public function testClipperClip()
    {
        $indexString = "
            1 1 1 1 1 1
            1 1 1 1 1 1
            1 1 1 1 1 1
            2 2 2 2 2 2
            2 2 2 2 2 2
            2 2 2 2 2 2
        ";
        $index2color = array(
            '1' => 0x00ff00,
            '2' => 0x0000ff,
        );
        $canvas = new StringCanvas(6, 6, $indexString, $index2color);

        $frame = new Frame();
        $frame->setCanvas($canvas);
        $frame->setLeft(1)->setTop(2);
        $frame->setClip(new ClippingArea(3, 1, 7, 7));

        $clipper = new Clipper();
        $clip = $clipper->getClip($frame, 6, 6);

        $this->assertSame(3, $clip->getLeft());
        $this->assertSame(1, $clip->getTop());
        $this->assertSame(4, $clip->getRight());
        $this->assertSame(3, $clip->getBottom());

        $frame = new Frame();
        $frame->setCanvas($canvas);
        $frame->setLeft(-2)->setTop(-2);
        $frame->setClip(new ClippingArea(-1, -2, 4, 3));

        $clipper = new Clipper();
        $clip = $clipper->getClip($frame, 6, 6);

        $this->assertSame(2, $clip->getLeft());
        $this->assertSame(2, $clip->getTop());
        $this->assertSame(4, $clip->getRight());
        $this->assertSame(3, $clip->getBottom());
    }
}