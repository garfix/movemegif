<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class GraphicExtension
{
    const EXTENSION_INTRODUCER = 0x21;
    const GRAPHIC_CONTROL_LABEL = 0xF9;
    const BLOCK_TERMINATOR = 0x00;

    /** @var int Size in bytes */
    private $blockSize;

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

    public function getContents()
    {
        $packedByte = ($this->disposalMethod * 4) + ($this->userInputFlag * 2) + $this->transparentColorFlag;

        $imageDescriptor = new ImageDescriptor();
        $imageData = new ImageData();

        return
            chr(self::EXTENSION_INTRODUCER) . chr(self::GRAPHIC_CONTROL_LABEL) .
            chr($this->blockSize) .
            $packedByte .
            pack('v', $this->delayTime) .
            chr($this->transparentColorIndex) .
            chr(self::BLOCK_TERMINATOR) .
            $imageDescriptor->getContents() .
            $imageData->getContents();
    }
}