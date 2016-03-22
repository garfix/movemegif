<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class ImageDescriptor
{
    const IMAGE_SEPARATOR = 0x2C;

    private $left = 0;

    private $top = 0;

    private $width = 10;

    private $height = 10;

    /** @var  int In [0, 1] */
    private $localColorTableFlag = 0;

    /** @var  int In [0, 1] */
    private $interlaceFlag = 0;

    /** @var  int In [0, 1] */
    private $sortFlag = 0;

    /** @var  int In [0..7] */
    private $sizeOfLocalColorTable = 0;

    public function getContents()
    {
        $packedByte =
            chr($this->localColorTableFlag * 128) .
            chr($this->interlaceFlag * 64) .
            chr($this->sortFlag * 32) .
            chr($this->sizeOfLocalColorTable);

        return
            chr(self::IMAGE_SEPARATOR) .
            pack('v', $this->left) .
            pack('v', $this->top) .
            pack('v', $this->width) .
            pack('v', $this->height) .
            chr($packedByte);
    }
}