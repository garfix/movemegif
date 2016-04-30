<?php

namespace movemegif\data;

use movemegif\domain\ClippingArea;
use movemegif\domain\GdCanvas;

/**
 * This class uses `imagegif` function to quickly generate the compressed pixel data
 *
 * @author Patrick van Bergen
 */
class GDAcceleratedPixelDataProducer implements PixelDataProducer
{
    /** @var  GdCanvas */
    private $gdCanvas;

    /** @var  ClippingArea */
    private $clippingArea;

    /** @var  GifData */
    private $gifData;

    public function __construct(GdCanvas $gdCanvas, ClippingArea $clippingArea)
    {
        $this->gdCanvas = $gdCanvas;
        $this->clippingArea = $clippingArea;

#todo: clipping!

        $resource = $this->gdCanvas->getResource();

        // make sure the resource is transparent, so the format is GIF 89a
        imagecolortransparent($resource, 0);

        ob_start();
        imagegif($resource);
        $imageData = ob_get_clean();

        $parser = new GifParser();
        $this->gifData = $parser->parseString($imageData);
    }

    public function getCompressedPixelData()
    {
        return $this->gifData->compressedPixelData;
    }

    public function getColorData()
    {
        return $this->gifData->globalColorData;
    }

    public function getColorTableSize()
    {
        return $this->gifData->globalColorTableSize;
    }
}