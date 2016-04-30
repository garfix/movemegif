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

    /** @var  bool */
    private $colorTableIsLocal;

    /** @var  int */
    private $colorTableSize;

    /** @var  int In [0, 1] Not used here. */
    private $interlaceFlag = 0;

    /** @var  int In [0, 1] Not used here.*/
    private $sortFlag = 0;

    public function __construct($width, $height, $left, $top, $colorTableIsLocal, $colorTableSize)
    {
        $this->width = $width;
        $this->height = $height;
        $this->left = $left;
        $this->top = $top;
        $this->colorTableIsLocal = $colorTableIsLocal;
        $this->colorTableSize = $colorTableSize;
    }

    public function getContents()
    {
        $localColorTableFlag = (int)$this->colorTableIsLocal;

        if ($localColorTableFlag) {
            $colorTableSize = $this->colorTableSize;
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