<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class ClippingArea
{
    private $left;
    private $top;
    private $right;
    private $bottom;

    public function __construct($left = PHP_INT_MAX, $top = PHP_INT_MAX, $right = -1, $bottom = -1)
    {
        $this->left = $left;
        $this->top = $top;
        $this->right = $right;
        $this->bottom = $bottom;
    }

    /**
     * @param int $x
     * @return $this
     */
    public function includeX($x)
    {
        if ($this->left > $x) {
            $this->left = $x;
        }
        if ($this->right < $x) {
            $this->right = $x;
        }

        return $this;
    }

    /**
     * @param int $y
     * @return $this
     */
    public function includeY($y)
    {
        if ($this->top > $y) {
            $this->top = $y;
        }
        if ($this->bottom < $y) {
            $this->bottom = $y;
        }

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     * @return $this
     */
    public function includePoint($x, $y)
    {
        $this->includeX($x);
        $this->includeY($y);

        return $this;
    }

    /**
     * Returns the union of this clipping area and $area,
     *
     * @param ClippingArea $area
     * @return ClippingArea
     */
    public function getUnion(ClippingArea $area)
    {
        return new ClippingArea(
            min($this->left, $area->left),
            min($this->top, $area->top),
            max($this->right, $area->right),
            max($this->bottom, $area->bottom)
        );
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
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @return int
     */
    public function getBottom()
    {
        return $this->bottom;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return max(0, $this->right - $this->left + 1);
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return max(0, $this->bottom - $this->top + 1);
    }
}