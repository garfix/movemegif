<?php

use movemegif\GifBuilder;

require_once __DIR__ . '/../php/autoloader.php';

$Builder = new GifBuilder();

$Builder->addImage();
$Builder->addRepeat();
$Builder->addImage();

$Builder->output();
