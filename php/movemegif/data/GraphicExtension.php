<?php

namespace movemegif\data;

use movemegif\exception\EmptyFrameException;

/**
 * @author Patrick van Bergen
 */
class GraphicExtension implements Extension
{
    const GRAPHIC_CONTROL_LABEL = 0xF9;

    /** @var array An array color indexes */
    private $pixelColorIndexes;

    /** @var ColorTable  */
    private $colorTable;

    /** @var  int $duration The time this frame is visible (in 1/100 seconds). */
    private $duration;

    /** @var int Frame image left position in [0..65535] */
    private $left;

    /** @var int Frame image top position in [0..65535] */
    private $top;

    /** @var int Frame image width in [0..65535] */
    private $width;

    /** @var int Frame image height in [0..65535] */
    private $height;

    /** @var int In [0..7] */
    private $disposalMethod;

    /** @var int In [0, 1] Probably not used by current browsers. Not used here. */
    private $userInputFlag = 0;

    /** @var int  */
    private $transparentColorIndex = null;

    public function __construct(array $pixelData, ColorTable $colorTable, $duration, $disposalMethod, $transparencyColor, $width, $height, $left, $top)
    {
        $this->colorTable = $colorTable;
        $this->duration = $duration;
        $this->disposalMethod = $disposalMethod;
        $this->width = $width;
        $this->height = $height;
        $this->left = $left;
        $this->top = $top;

        if (empty($pixelData)) {
            throw EmptyFrameException::create();
        }

        $this->pixelColorIndexes = array();
        foreach ($pixelData as $color) {
            $this->pixelColorIndexes[] = $colorTable->getColorIndex($color);
        }

        if ($transparencyColor !== null) {
            $this->transparentColorIndex = $colorTable->getColorIndex($transparencyColor);
        }
    }

    public function getContents()
    {
        $transparencyColorFlag = $this->transparentColorIndex !== null ? 1 : 0;
        $transparencyColorIndex = $transparencyColorFlag ? $this->transparentColorIndex : 0;
        $packedByte = ($this->disposalMethod * 4) + ($this->userInputFlag * 2) + $transparencyColorFlag;

        $imageDescriptor = new ImageDescriptor($this->width, $this->height, $this->left, $this->top, $this->colorTable);
        $imageData = new ImageData($this->pixelColorIndexes, $this->colorTable->getTableSize());

        return
            chr(self::EXTENSION_INTRODUCER) . chr(self::GRAPHIC_CONTROL_LABEL) .
            DataSubBlock::createBlocks(chr($packedByte) . pack('v', $this->duration) . chr($transparencyColorIndex)) .
            DataSubBlock::createBlocks('') .
            $imageDescriptor->getContents() .
            ($this->colorTable->isLocal() ? $this->colorTable->getContents() : '') .
            $imageData->getContents();
    }
}