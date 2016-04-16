<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class ClippingArea
{
    private $left = PHP_INT_MAX;
    private $top = PHP_INT_MAX;
    private $right = 0;
    private $bottom = 0;

    /**
     * @param int $x
     * @return $this
     */
    public function includeX($x)
    {
        if ($x < $this->left) {
            $this->left = $x;
        } elseif ($x > $this->right) {
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
        if ($y < $this->top) {
            $this->top = $y;
        } elseif ($y > $this->bottom) {
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
        $newArea = new ClippingArea();
        $newArea
            ->includeX($area->left)
            ->includeX($area->right)
            ->includeY($area->top)
            ->includeY($area->bottom);

        return $newArea;
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
        return $this->right - $this->left + 1;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->bottom - $this->top + 1;
    }
}