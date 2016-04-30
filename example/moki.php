<?php
/**
 * This example shows a background on frame 1, followed by images of a running dog (Moki) with a transparent background on the other frames.
 *
 * Note! This technique has an important shortcoming: when looping, the background will be shown by its own, each new loop.
 * It is not possible to start looping _after_ the background has been drawn.
 * This means that if the dog was running at a fixed position, you would see a flash of background every 10th frame.
 *
 * The image of the running dog was taken from http://almirah.deviantart.com/art/Moki-Run-Cycle-174572876
 */

use movemegif\domain\FileImageCanvas;
use movemegif\GifBuilder;

// just for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// include movemegif's namespace
require_once __DIR__ . '/../php/autoloader.php';

$builder = new GifBuilder(320, 180);
$builder->setRepeat();

// background image
$builder->addFrame()
    ->setCanvas(new FileImageCanvas(__DIR__ . '/moki/landscape.jpg'));

// dogs runs from left to the right
$imageIndex = 0;
for ($x = -140; $x <= 310; $x += 10) {

    $builder->addFrame()
        // load single frame from GIF file, and autodetect transparency color
        ->setCanvas(new FileImageCanvas(__DIR__ . '/moki/' . $imageIndex . '.gif'))
        // number of 1/100 seconds per frame
        ->setDuration(8)
        // position this frame on the bottom half of the image
        ->setTop(60)->setLeft($x)
        // when done painting one frame of the dog, restore the state to just before the dog was drawn
        ->setDisposalToOverwriteWithPreviousFrame()
    ;

    // next dog image
    if (++$imageIndex == 10) {
        $imageIndex = 0;
    }
}

$builder->output('moki.gif');
