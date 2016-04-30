<?php

namespace movemegif\domain;

use movemegif\exception\DurationTooSmallException;

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
    private $useLocalColorTable = true;

    /** @var int  */
    private $duration = 0;

    /** @var int  */
    private $disposalMethod = self::DISPOSAL_UNDEFINED;

    /** @var int Frame image left position in [0..65535] */
    private $left = 0;

    /** @var int Frame image top position in [0..65535] */
    private $top = 0;

    /** @var ClippingArea|null */
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
     * Create a custom color table for this frame alone.
     *
     * @return $this
     */
    public function setUseLocalColorTable()
    {
        $this->useLocalColorTable = true;
        return $this;
    }

    /**
     * Merge colors in the global color table. This is the default value.
     *
     * @return $this
     */
    public function setUseGlobalColorTable()
    {
        $this->useLocalColorTable = false;
        return $this;
    }

    /**
     * Duration in 1/100ths of a second.
     *
     * But this is theory! In practice browsers don't respect very small frame rates, and the smallest framerate
     * that is useful is actually 2. You must experiment with some actual browsers.
     *
     * This library throws an exception when you use value 0 or 1 anyway.
     *
     * If you _must_ use 0 or 1, set $forced = true
     *
     * @see http://superuser.com/questions/569924/why-is-the-gif-i-created-so-slow
     *
     * @param int $duration The time this frame is visible (in 1/100 seconds). Use a value between 0 and 65535
     * @param bool $forced
     * @return $this
     * @throws DurationTooSmallException
     */
    public function setDuration($duration, $forced = false)
    {
        if ($duration < 2) {
            if (!$forced) {
                throw DurationTooSmallException::create();
            }
        }

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
     * @return int|null
     */
    public function getTransparencyColor()
    {
        return $this->getCanvas()->getTransparencyColor();
    }

    /**
     * @return int
     */
    public function getDisposalMethod()
    {
        return $this->disposalMethod;
    }

    /**
     * @param int $clipLeft
     * @param int $clipTop
     * @param int $clipRight
     * @param int $clipBottom
     * @return int[]
     */
    public function getPixels($clipLeft, $clipTop, $clipRight, $clipBottom)
    {
        return $this->getCanvas()->getPixels($clipLeft, $clipTop, $clipRight, $clipBottom);
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
     * The pixels of the named coordinates are included.
     *
     * @param ClippingArea $clip
     * @return $this
     */
    public function setClip(ClippingArea $clip = null)
    {
        $this->clip = $clip;
        return $this;
    }

    /**
     * @return ClippingArea
     */
    public function getClip()
    {
        if ($this->clip) {
            return $this->clip;
        } else {
            return new ClippingArea(0, 0, $this->getWidth() - 1, $this->getHeight() - 1);
        }
    }
}