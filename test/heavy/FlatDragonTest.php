<?php

use movemegif\domain\GdCanvas;

require_once __DIR__ . '/../../php/autoloader.php';

/**
 * @author Patrick van Bergen
 */
class FlatDragonTest extends PHPUnit_Framework_TestCase
{
    public function testLargeImage()
    {
        $builder = new \movemegif\GifBuilder(200, 100);

        $canvas = new GdCanvas(200, 100);

        $imagePath = __DIR__ . '/../resources/flat-dragon.gif';
        $source = imagecreatefromgif($imagePath);
        imagecopy($canvas->getResource(), $source, 0, 0, 0, 0, 200, 100);

        $builder->addFrame()->setDuration(50)->setCanvas($canvas)->setUseGlobalColorTable();

        $contents = $builder->getContents();

        $this->assertSame($contents, file_get_contents($imagePath));
    }

    public function testLargeImageUsingAccelleratedPixelDataProducer()
    {
        $builder = new \movemegif\GifBuilder(200, 100);

        $canvas = new GdCanvas(200, 100);

        $imagePath = __DIR__ . '/../resources/local-dragon.gif';
        $source = imagecreatefromgif($imagePath);
        imagecopy($canvas->getResource(), $source, 0, 0, 0, 0, 200, 100);

        $builder->addFrame()->setDuration(50)->setCanvas($canvas)->setUseLocalColorTable();

        $contents = $builder->getContents();

        $this->assertSame($contents, file_get_contents($imagePath));
    }

}