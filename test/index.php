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

    $Builder = new GifBuilder();

    $Builder->addFrame()->setPixelsAndColors($pixelIndexes, $colorTable)->setUseLocalColorTable(false);

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

    $Builder = new GifBuilder();

    $Builder->addFrame()
        ->setPixelsAndColors($pixelIndexes, $colorTable)
        ->setUseLocalColorTable(false)
        ->setDuration(100);

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

    $Builder->addFrame()
        ->setPixelsAndColors($pixelIndexes, $colorTable)
        ->setUseLocalColorTable(false);

    $Builder->output();
}

image2();