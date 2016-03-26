<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class GraphicExtension
{
    const EXTENSION_INTRODUCER = 0x21;
    const GRAPHIC_CONTROL_LABEL = 0xF9;

    /** @var array An array color indexes */
    private $pixelColorIndexes;

    /** @var ColorTable  */
    private $colorTable = null;

    /** @var int In [0..7] */
    private $disposalMethod = 0;

    /** @var int In [0, 1] */
    private $userInputFlag = 0;

    /** @var int In [0, 1] */
    private $transparentColorFlag = 0;

    /** @var  int */
    private $delayTime = 0;

    /** @var int  */
    private $transparentColorIndex = 0;

    public function __construct(array $pixelData, ColorTable $colorTable)
    {
        $this->colorTable = $colorTable;

        $this->pixelColorIndexes = array();
        foreach ($pixelData as $color) {
            $this->pixelColorIndexes[] = $colorTable->getColorIndex($color);
        }
    }

    public function getContents()
    {
        $packedByte = ($this->disposalMethod * 4) + ($this->userInputFlag * 2) + $this->transparentColorFlag;

        $imageDescriptor = new ImageDescriptor();
        $imageData = new ImageData($this->pixelColorIndexes, $this->colorTable->getTableSize());

        return
            chr(self::EXTENSION_INTRODUCER) . chr(self::GRAPHIC_CONTROL_LABEL) .
            DataSubBlock::createBlocks(chr($packedByte) . pack('v', $this->delayTime) . chr($this->transparentColorIndex)) .
            DataSubBlock::createBlocks('') .
            $imageDescriptor->getContents() .
            ($this->colorTable->isLocal() ? $this->colorTable->getContents() : '') .
            $imageData->getContents();
    }
}