<?php
/**
 * This script builds a simple image and outputs it to the client.
 */

use movemegif\GifBuilder;

require_once __DIR__ . '/../php/autoloader.php';

$Builder = new GifBuilder();

$Builder->addImage();
$Builder->addRepeat();
$Builder->addImage();

$Builder->output();
