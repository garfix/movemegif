<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class LogicalScreenDescriptor
{
    /** @var int GIF image width in [0..65535] */
    private $width;

    /** @var int GIF image height in [0..65535] */
    private $height;

    /** @var int Bits per pixel, minus one. In [0..7]. Is probably not used; if it is, 7 would be more appropriate. */
    private $colorResolution = 1;

    /** @var int 1 = colors are sorted in decreasing importance. Not used here. */
    private $sortFlag = 0;

    /** @var int Index of background color in Global Color Table */
    private $backgroundColorIndex = 0;

    /** @var int Probably not used by clients. Not used here. */
    private $pixelAspectRatio = 0;

    public function __construct($width, $height, ColorTable $colorTable, $backgroundColor)
    {
        $this->width = $width;
        $this->height = $height;
        $this->colorTable = $colorTable;
        $this->backgroundColorIndex = ($backgroundColor !== null ? $this->colorTable->getColorIndex($backgroundColor) : 0);
    }

    public function getContents()
    {
        $colorTableSize = $this->colorTable->getTableSize();

        $globalColorTableFlag = $colorTableSize != 0;

        if ($globalColorTableFlag) {
            /** @var int Actual number of colors in the table = 2 ^ ($sizeOfGlobalColorTable + 1)  */
            $sizeOfGlobalColorTable = Math::getExponent($colorTableSize) - 1;
        } else {
            $sizeOfGlobalColorTable = 0;
        }

        $packedByte = ($globalColorTableFlag * 128) + ($this->colorResolution * 16) + ($this->sortFlag * 8) + $sizeOfGlobalColorTable;

        return pack('v', $this->width) . pack('v', $this->height) . chr($packedByte) . chr($this->backgroundColorIndex) . chr($this->pixelAspectRatio);
    }
}