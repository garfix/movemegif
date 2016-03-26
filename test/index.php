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

    $Builder->addFrame(10, 10)->setPixelsAndColors($pixelIndexes, $colorTable)->setUseLocalColorTable(false);

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
        ->setPixelsAndColors($pixelIndexes, $colorTable)
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
        ->setPixelsAndColors($pixelIndexes, $colorTable)
        ->setUseLocalColorTable(false)
        ->setDuration(50);

    $Builder->setRepeat(1);

    $Builder->output();
}

function image3()
{
    $bytes = "47 49 46 38 39 61 0A 00 0A 00 91 00 00 FF 00 00 00 00 FF FF FF FF 00 00 00 21 F9 04 00 00 00 00 00 2C 00 00 00 00 0A 00 0A 00 00 02 16 84 1D 99 87 1A 0C DC 33 A2 0A 75 EC 95 FA A8 DE 60 8C 04 91 4C 01 00 3B";

    header('Content-type: image/gif');
    header('Content-disposition: inline; filename="name.gif"');

    foreach (explode(" ", $bytes) as $byte) {
        echo hex2bin($byte);
    }
}

image3();