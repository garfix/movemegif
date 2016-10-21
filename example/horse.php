<?php
/**
 * A simple animation created from with 8 png images.
 *
 * The horse images were taken from this Wikipedia page
 *
 * https://en.wikipedia.org/wiki/Animated_cartoon
 */

use movemegif\domain\FileImageCanvas;
use movemegif\GifBuilder;

// just for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// include movemegif's namespace
require_once __DIR__ . '/../php/autoloader.php';

// no width and height specified: they will be taken from the first frame
$builder = new GifBuilder();
$builder->setRepeat();

for ($i = 1; $i <= 8; $i++) {

    $builder->addFrame()
        ->setCanvas(new FileImageCanvas(__DIR__ . '/horse/' . $i . '.png'))
        ->setDuration(8);
}

$builder->output('horse.gif');
