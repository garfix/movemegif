<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class GifData
{
    /** @var  int */
    public $imageWidth;

    /** @var  int */
    public $imageHeight;

    /** @var  bool */
    public $usesGlobalColorTable;

    /** @var  int Number of color table entries */
    public $globalColorTableSize;

    /** @var  string */
    public $globalColorData;

    /** @var  bool */
    public $usesLocalColorTable;

    /** @var  int Number of color table entries */
    public $localColorTableSize;

    /** @var  string GIF LZW compressed data */
    public $compressedPixelData;
}