<?php

use pong\Pong;

// just for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// this may take some time
set_time_limit(200);

// include movemegif's namespace
require_once __DIR__ . '/../php/autoloader.php';
// include pong namespace
require_once __DIR__ . '/autoloader.php';

$pong = new Pong();
$builder = $pong->getBuilder();
$builder->output('pong.gif');
