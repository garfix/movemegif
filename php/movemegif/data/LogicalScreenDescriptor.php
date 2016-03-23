<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class LogicalScreenDescriptor
{
    /** @var int GIF image width in [0..65535] */
    private $width = 10;

    /** @var int GIF image height in [0..65535] */
    private $height = 10;

    /** @var int 1 = color table available; 0 = no color table available */
    private $globalColorTableFlag = 1;

    /** @var int Bits per pixel, minus one. In [0..7] */
    private $colorResolution = 1;

    /** @var int 1 = colors are sorted in decreasing importance */
    private $sortFlag = 0;

    /** @var int Actual number of colors in the table = 2 ^ ($sizeOfGlobalColorTable + 1)  */
    private $sizeOfGlobalColorTable = 1;

    /** @var int Index of background color in Global Color Table */
    private $backgroundColorIndex = 0;

    /** @var int Probably not used by clients */
    private $pixelAspectRatio = 0;

    public function getContents()
    {
        $packedByte = ($this->globalColorTableFlag * 128) + ($this->colorResolution * 16) + ($this->sortFlag * 8) + $this->sizeOfGlobalColorTable;

        return pack('v', $this->width) . pack('v', $this->height) . chr($packedByte) . chr($this->backgroundColorIndex) . chr($this->pixelAspectRatio);
    }
}