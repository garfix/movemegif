<?php
/**
 * This script builds a simple image and outputs it to the client.
 */

use movemegif\GifBuilder;

require_once __DIR__ . '/../php/autoloader.php';

function image1()
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

    $colorTable = array(
        '0' => 0xFFFFFF,
        '1' => 0xFF0000,
        '2' => 0x0000FF,
        '3' => 0x000000
    );

    $Builder = new GifBuilder(10, 10);

    $Builder->addFrame(10, 10)->setPixelsAsIndexedColors($pixelIndexes, $colorTable)->setUseLocalColorTable(false);

    $Builder->output();
}

function image2()
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

    $colorTable = array(
        '0' => 0xFFFFFF,
        '1' => 0xFF0000,
        '2' => 0x0000FF,
        '3' => 0x000000
    );

    $Builder = new GifBuilder(10, 10);

    $Builder->addFrame(10, 10)
        ->setPixelsAsIndexedColors($pixelIndexes, $colorTable)
        ->setUseLocalColorTable(false)
        ->setDuration(50);

    $pixelIndexes = "
        1 1 1 1 1 2 2 2 2 2
        1 1 1 1 1 2 2 2 2 2
        1 1 1 1 1 2 2 2 2 2
        1 1 1 3 3 3 3 2 2 2
        1 1 1 3 3 3 3 2 2 2
        2 2 2 3 3 3 3 1 1 1
        2 2 2 3 3 3 3 1 1 1
        2 2 2 2 2 1 1 1 1 1
        2 2 2 2 2 1 1 1 1 1
        2 2 2 2 2 1 1 1 1 1
    ";

    $Builder->addFrame(10, 10)
        ->setPixelsAsIndexedColors($pixelIndexes, $colorTable)
        ->setUseLocalColorTable(false)
        ->setDuration(50);

    $Builder->setRepeat(1);

    $Builder->output();
}

function image3()
{
    $bytes = "47 49 46 38 39 61 06 00 06 00 91 00 00 FF FF FF FF 00 00 00 00 00 00 00 00 21 F9 04 00 32 00 00 00 2C 00 00 00 00 06 00 06 00 00 02 0A 84 11 71 A8 97 B9 A0 6B A6 00 00 21 F9 04 00 00 00 00 00 2C 02 00 03 00 04 00 03 00 00 02 04 8C 0D 70 56 00 3B";

//    header('Content-type: image/gif');
//    header('Content-disposition: inline; filename="name.gif"');

    $frame1 = "
            0 1 2 3 1 1
            1 1 1 1 1 1
            1 1 1 1 1 1
            1 1 1 1 1 1
            1 1 1 1 1 1
            1 1 1 1 1 1
        ";

    $index2color = array(
        '0' => 0x00FF00,
        '1' => 0xFF0000,
        '2' => 0xc0c0c0,
        '3' => 0x0000FF,
    );

    $builder = new GifBuilder(6, 6);
    $builder->setBackgroundColor(0x00FF00);
    $builder->addFrame()->setPixelsAsIndexedColors($frame1, $index2color)->setDuration(50)
        ->setUseLocalColorTable(false);

    $frame2 = "
            2 2 2 3 3 3
            2 2 2 3 3 3
            2 2 2 3 3 3
        ";

    $builder->addFrame(6, 3, 0, 0)->setPixelsAsIndexedColors($frame2, $index2color)->setDuration(50)->setDisposalToOverwriteWithBackgroundColor()
        ->setUseLocalColorTable(false);

    $builder->addFrame(6, 3, 0, 3)->setPixelsAsIndexedColors($frame2, $index2color)->setDuration(50)->setDisposalToOverwriteWithBackgroundColor()
        ->setUseLocalColorTable(false);

//    $builder->output();exit;

    $bytes = $builder->getContents();

    echo \movemegif\data\Formatter::byteString2hexString($bytes);
    exit;
}

image3();