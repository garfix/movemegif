<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class GraphicExtension implements Extension
{
    const EXTENSION_INTRODUCER = 0x21;
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

    /** @var int In [0, 1] */
    private $transparentColorFlag = 0;

    /** @var int  */
    private $transparentColorIndex = 0;

    public function __construct(array $pixelData, ColorTable $colorTable, $duration, $disposalMethod, $width, $height, $left, $top)
    {
        $this->colorTable = $colorTable;
        $this->duration = $duration;
        $this->disposalMethod = $disposalMethod;
        $this->width = $width;
        $this->height = $height;
        $this->left = $left;
        $this->top = $top;

        $this->pixelColorIndexes = array();
        foreach ($pixelData as $color) {
            $this->pixelColorIndexes[] = $colorTable->getColorIndex($color);
        }
    }

    public function getContents()
    {
        $packedByte = ($this->disposalMethod * 4) + ($this->userInputFlag * 2) + $this->transparentColorFlag;

        $imageDescriptor = new ImageDescriptor($this->width, $this->height, $this->left, $this->top, $this->colorTable);
        $imageData = new ImageData($this->pixelColorIndexes, $this->colorTable->getTableSize());

        return
            chr(self::EXTENSION_INTRODUCER) . chr(self::GRAPHIC_CONTROL_LABEL) .
            DataSubBlock::createBlocks(chr($packedByte) . pack('v', $this->duration) . chr($this->transparentColorIndex)) .
            DataSubBlock::createBlocks('') .
            $imageDescriptor->getContents() .
            ($this->colorTable->isLocal() ? $this->colorTable->getContents() : '') .
            $imageData->getContents();
    }
}