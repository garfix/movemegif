<?php

use movemegif\data\ColorTable;
use movemegif\data\GraphicExtension;
use movemegif\domain\Frame;
use movemegif\domain\StringCanvas;
use movemegif\exception\ColorNotFoundException;
use movemegif\exception\DurationTooSmallException;
use movemegif\exception\EmptyFrameException;
use movemegif\exception\InvalidDimensionsException;
use movemegif\exception\MovemegifException;
use movemegif\exception\TooManyColorsException;
use movemegif\GifBuilder;

require_once __DIR__ . '/../../php/autoloader.php';

/**
 * Tests all exceptions
 *
 * @author Patrick van Bergen
 */
class ExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testTooManyColorsException()
    {
        $count = 0;

        $pixelIndexes = "";

        $index2color = array();

        for ($i = 0; $i < 256; $i++) {
            $pixelIndexes .= ' ' . (string)$i;
            $index2color[$i] = $i;
        }

        $builder = new GifBuilder(16, 16);
        $builder->addFrame()->setCanvas(new StringCanvas(16, 16, $pixelIndexes, $index2color));

        try {
            $builder->getContents();
        } catch (MovemegifException $e) {
            $count++;
        }

        for ($i = 0; $i < 16; $i++) {
            $pixelIndexes .= ' ' . (string)256;
        }
        $index2color[256] = 256;

        $builder = new GifBuilder(16, 17);
        $builder->addFrame()->setCanvas(new StringCanvas(16, 17, $pixelIndexes, $index2color));

        try {
            $builder->getContents();
        } catch (TooManyColorsException $e) {
            $count++;
        }

        $this->assertSame(1, $count);
    }

    public function testDurationTooSmallException()
    {
        $count = 0;

        $frame = new Frame();

        try {

            $frame->setDuration(1);

        } catch (DurationTooSmallException $e) {
            $count++;
        }

        $this->assertSame(1, $count);
    }

//    public function testEmptyFrameException()
//    {
//        $count = 0;
//
//        try {
//
//            new GraphicExtension(array(), new ColorTable(0), 2, 1, 1, 1, 1, 0, 0);
//
//        } catch (EmptyFrameException $e) {
//            $count++;
//        }
//
//        $this->assertSame(1, $count);
//    }

    public function testColorNotFoundException()
    {
        $count = 0;

        $indexString = '
            1 2
            2 1
        ';

        $index2color = array(
            '1' => 0x00ff00
        );

        try {

            $canvas = new StringCanvas(2, 2, $indexString, $index2color);

        } catch (ColorNotFoundException $e) {
          $count++;
        }

        $this->assertSame(1, $count);
    }


    public function testInvalidDimensionsException()
    {
        $count = 0;

        $indexString = '
            1 2
            2 1
        ';

        $index2color = array(
            '1' => 0x00ff00,
            '2' => 0xff0000,
        );

        try {

            $canvas = new StringCanvas(2, 3, $indexString, $index2color);

        } catch (InvalidDimensionsException $e) {
            $count++;
        }

        $this->assertSame(1, $count);
    }
}