<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class ImageDescriptor
{
    const IMAGE_SEPARATOR = 0x2C;

    /** @var int Frame image left position in [0..65535] */
    private $left;

    /** @var int Frame image top position in [0..65535] */
    private $top;

    /** @var int Frame image width in [0..65535] */
    private $width;

    /** @var int Frame image height in [0..65535] */
    private $height;

    /** @var  ColorTable */
    private $colorTable;

    /** @var  int In [0, 1] */
    private $interlaceFlag = 0;

    /** @var  int In [0, 1] */
    private $sortFlag = 0;

    public function __construct($width, $height, $left, $top, ColorTable $colorTable)
    {
        $this->width = $width;
        $this->height = $height;
        $this->left = $left;
        $this->top = $top;
        $this->colorTable = $colorTable;
    }

    public function getContents()
    {
        $localColorTableFlag = (int)$this->colorTable->isLocal();

        if ($localColorTableFlag) {
            $colorTableSize = $this->colorTable->getTableSize();
            $sizeOfLocalColorTable = $colorTableSize ? Math::getExponent($colorTableSize) - 1 : 0;
        } else {
            $sizeOfLocalColorTable = 0;
        }

        $packedByte =
            $localColorTableFlag * 128 +
            $this->interlaceFlag * 64 +
            $this->sortFlag * 32 +
            $sizeOfLocalColorTable;

        return
            chr(self::IMAGE_SEPARATOR) .
            pack('v', $this->left) .
            pack('v', $this->top) .
            pack('v', $this->width) .
            pack('v', $this->height) .
            chr($packedByte);
    }
}