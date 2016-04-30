<?php

namespace movemegif\data;

use movemegif\exception\EmptyFrameException;

/**
 * @author Patrick van Bergen
 */
class PHPPixelDataProducer implements PixelDataProducer
{
    /** @var array An array color indexes */
    private $pixelColorIndexes;

    /** @var  ColorTable */
    private $colorTable;

    public function __construct(array $pixelData, ColorTable $colorTable)
    {
        $this->colorTable = $colorTable;

        if (empty($pixelData)) {
            throw EmptyFrameException::create();
        }

        $this->pixelColorIndexes = array();
        foreach ($pixelData as $color) {
            $this->pixelColorIndexes[] = $colorTable->getColorIndex($color);
        }
    }

    public function getCompressedPixelData()
    {
        $imageData = new ImageData($this->pixelColorIndexes, $this->colorTable->getTableSize());

        return $imageData->getContents();
    }

    public function getColorData()
    {
        return ($this->colorTable->isLocal() ? $this->colorTable->getContents() : '');
    }

    public function getColorTableSize()
    {
        return $this->colorTable->getTableSize();
    }
}