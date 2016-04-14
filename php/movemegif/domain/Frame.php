<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class Frame
{
    const DISPOSAL_UNDEFINED = 0;
    const DISPOSAL_LEAVE = 1;
    const DISPOSAL_RESTORE_TO_BACKGROUND = 2;
    const DISPOSAL_RESTORE_TO_PREVIOUS_FRAME = 3;

    /** @var Canvas */
    private $canvas = null;

    /** @var bool  */
    private $useLocalColorTable = false;

    /** @var int  */
    private $duration = 0;

    /** @var int  */
    private $disposalMethod = self::DISPOSAL_UNDEFINED;

    /** @var int|null The 0xRRGGBB color that specifies the pixels that will be made transparent (null = none) */
    private $transparencyColor = null;

    /** @var int Frame image left position in [0..65535] */
    private $left;

    /** @var int Frame image top position in [0..65535] */
    private $top;

    private $clip = null;

    /**
     * Sets the left pixel position of this frame's canvas with respect to the GIF's canvas.
     *
     * @param int $left
     * @return $this
     */
    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }

    /**
     * Sets the top pixel position of this frame's canvas with respect to the GIF's canvas.
     *
     * @param $top
     * @return $this;
     */
    public function setTop($top)
    {
        $this->top = $top;
        return $this;
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
        return $this->getCanvas()->getWidth();
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->getCanvas()->getHeight();
    }

    /**
     * @param Canvas $canvas
     * @return $this
     */
    public function setCanvas(Canvas $canvas)
    {
        $this->canvas = $canvas;
        return $this;
    }

    /**
     * Create a custom color table for this frame alone (true) or merge colors in the global color table (false).
     * By default this is set to false (i.e. all frames share the global color table).
     *
     * @param boolean $useLocalColorTable
     * @return $this
     */
    public function setUseLocalColorTable($useLocalColorTable = true)
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
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Specify what happens at the end of this frame: overwrite all pixels of this frame with the background.
     *
     * Originally this meant that the GIF's background color was used. In most browsers, the frame is 'erased' and the part of the page behind it 'shines through'.
     *
     * @return $this
     */
    public function setDisposalToOverwriteWithBackground()
    {
        $this->disposalMethod = self::DISPOSAL_RESTORE_TO_BACKGROUND;
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
     * @param int $color The 0xRRGGBB color that specifies which pixels should be made transparent.
     * @return $this
     */
    public function setTransparencyColor($color)
    {
        $this->transparencyColor = $color;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTransparencyColor()
    {
        return $this->transparencyColor;
    }

    /**
     * @return int
     */
    public function getDisposalMethod()
    {
        return $this->disposalMethod;
    }

    /**
     * @return int[]
     */
    public function getPixels()
    {
        return $this->getCanvas()->getPixels();
    }

    public function getCanvas()
    {
        if (!$this->canvas) {

            $indexString = "
                1 1 1 1 1 2 2 2 2 2
                1 1 1 1 1 2 2 2 2 2
                1 1 1 1 1 2 2 2 2 2
                1 1 1 0 0 0 0 2 2 2
                1 1 1 0 0 0 0 2 2 2
                2 2 2 0 0 0 0 1 1 1
                2 2 2 0 0 0 0 1 1 1
                2 2 2 2 2 1 1 1 1 1
                2 2 2 2 2 1 1 1 1 1
                2 2 2 2 2 1 1 1 1 1";

            $index2color = array(
                '0' => 0xFFFFFF,
                '1' => 0xFF0000,
                '2' => 0x0000FF,
                '3' => 0x000000
            );

            $this->canvas = new StringCanvas(10, 10, $indexString, $index2color);

        }

        return $this->canvas;
    }

    /**
     * @return boolean
     */
    public function usesLocalColorTable()
    {
        return $this->useLocalColorTable;
    }

    /**
     * Changes the part of this frame that is actually used in the image.
     *
     * @param int $x1 Leftmost pixel that will be used
     * @param int $y1 Topmost pixel
     * @param int $x2 Rightmost pixel
     * @param int $y2 Bottommost pixel
     * @return $this
     */
    public function setClip($x1, $y1, $x2, $y2)
    {
        $this->clip = array($x1, $y1, $x2, $y2);
        return $this;
    }
}