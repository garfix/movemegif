<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class Frame
{
    const DISPOSAL_UNDEFINED = 0;
    const DISPOSAL_LEAVE = 1;
    const DISPOSAL_RESTORE_TO_BG_COLOR = 2;
    const DISPOSAL_RESTORE_TO_PREVIOUS_FRAME = 3;

    /** @var int[] An array of 0xRRGGBB colors */
    private $pixels = null;

    /** @var bool  */
    private $useLocalColorTable = false;

    /** @var int  */
    private $duration = 0;

    /** @var int  */
    private $disposalMethod = self::DISPOSAL_UNDEFINED;

    /** @var int Frame image left position in [0..65535] */
    private $left;

    /** @var int Frame image top position in [0..65535] */
    private $top;

    /** @var int Frame image width in [0..65535] */
    private $width;

    /** @var int Frame image height in [0..65535] */
    private $height;

    public function __construct($width, $height, $left, $top)
    {
        $this->width = $width;
        $this->height = $height;
        $this->left = $left;
        $this->top = $top;
    }

    /**
     * @return int
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @return int
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Enter this image's data as a string of indexes and a indexed color table.
     *
     * For example:
     *
     * $pixels = "
     *     1 1 2 2
     *     1 0 0 2
     *     2 2 1 1
     * ";
     *
     * $colors = array(
     *     0 => 0xFFFFFF,
     *     1 => 0xFF0000,
     *     2 => 0x0000FF
     * )
     *
     * Each color is coded as 0x00RRGGBB (unused, red, green, blue bytes)
     *
     * @param string $pixelIndexes A whitespace separated string of color indexes.
     * @param int[] $index2color An index 2 RGB color map.
     * @return $this
     */
    public function setPixelsAsIndexedColors($pixelIndexes, array $index2color)
    {
        $array = array();

        preg_match_all('/(\d+)/', $pixelIndexes, $matches);

        foreach ($matches[1] as $match) {

            $index = $match[0];

            if (array_key_exists($index, $index2color)) {
                $array[] = $index2color[$index];
            } else {
#todo
            }

        }

        $this->pixels = $array;

        if (count($array) != $this->width * $this->height) {
#todo throw error
        }

        return $this;
    }

    /**
     * Create a custom color table for this frame alone (true) or merge colors in the global color table (false).
     *
     * @param boolean $useLocalColorTable
     * @return $this
     */
    public function setUseLocalColorTable($useLocalColorTable)
    {
        $this->useLocalColorTable = $useLocalColorTable;
        return $this;
    }

    /**
     * @param int $duration The time this frame is visible (in 1/100 seconds).
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return int The time this frame is visible (in 1/100 seconds).
     */
    public function getduration()
    {
        return $this->duration;
    }

    /**
     * Specify what happens at the end of this frame: overwrite all pixels of this frame with the background color of the canvas.
     * @return $this
     */
    public function setDisposalToOverwriteWithBackgroundColor()
    {
        $this->disposalMethod = self::DISPOSAL_RESTORE_TO_BG_COLOR;
        return $this;
    }

    /**
     * Specify what happens at the end of this frame: overwrite all pixels of this frame with pixels of the previous frame.
     * @return $this
     */
    public function setDisposalToOverwriteWithPreviousFrame()
    {
        $this->disposalMethod = self::DISPOSAL_RESTORE_TO_PREVIOUS_FRAME;
        return $this;
    }

    /**
     * @return int
     */
    public function getDisposalMethod()
    {
        return $this->disposalMethod;
    }

    public function getPixels()
    {
        if ($this->pixels === null) {

            $this->setPixelsAsIndexedColors("
                1 1 1 1 1 2 2 2 2 2
                1 1 1 1 1 2 2 2 2 2
                1 1 1 1 1 2 2 2 2 2
                1 1 1 0 0 0 0 2 2 2
                1 1 1 0 0 0 0 2 2 2
                2 2 2 0 0 0 0 1 1 1
                2 2 2 0 0 0 0 1 1 1
                2 2 2 2 2 1 1 1 1 1
                2 2 2 2 2 1 1 1 1 1
                2 2 2 2 2 1 1 1 1 1",

                array(
                    '0' => 0xFFFFFF,
                    '1' => 0xFF0000,
                    '2' => 0x0000FF,
                    '3' => 0x000000
                )
            );

        }

        return $this->pixels;
    }

    /**
     * @return boolean
     */
    public function usesLocalColorTable()
    {
        return $this->useLocalColorTable;
    }
}