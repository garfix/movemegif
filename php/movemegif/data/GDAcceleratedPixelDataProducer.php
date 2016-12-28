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

        $clippingWidth = $clippingArea->getRight() - $clippingArea->getLeft() + 1;
        $clippingHeight = $clippingArea->getBottom() - $clippingArea->getTop() + 1;
        $clippedResource = imagecreate($clippingWidth, $clippingHeight);

        imagecopy(
            $clippedResource, $this->gdCanvas->getResource(),
            0, 0,
            $clippingArea->getLeft(), $clippingArea->getTop(),
            $clippingWidth, $clippingHeight);

        // make sure there is one color specified as transparent, to enforce GIF89a in imagegif
        // fix by T Y for issue https://www.phpclasses.org/discuss/package/9748/thread/3/
        if (-1 === imagecolortransparent($clippedResource, 0)) {
            imagecolortransparent($clippedResource, imagecolorallocate($clippedResource, 255, 255, 255));
        }

        ob_start();
        imagegif($clippedResource);
        $imageData = ob_get_clean();

        imagedestroy($clippedResource);

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