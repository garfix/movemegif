<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class ColorTable
{
    /** @var  bool Does this table contain the color of just one image? */
    private $local;

    /** @var array An map of colors [index => color int] */
    private $colorIndexes = array();

    public function __construct($local)
    {
        $this->local = $local;
    }

    /**
     * @return bool
     */
    public function isLocal()
    {
        return $this->local;
    }

    public function getColorIndex($color)
    {
        $key = array_search($color, $this->colorIndexes);

        if ($key === false) {
            $key = count($this->colorIndexes);
            if ($key == 256) {
#todo exception: too many colors
            }

            $this->colorIndexes[] = $color;
        }

        return $key;
    }

    /**
     * Returns the number of entries in this table (rounded to the nearest power of two).
     *
     * @return int
     */
    public function getTableSize()
    {
        return Math::firstPowerOfTwo(count($this->colorIndexes));
    }

    public function getContents()
    {
        $result = '';
        $colorCount = count($this->colorIndexes);
        $maxI = Math::firstPowerOfTwo($colorCount);

        for ($i = 0; $i < $maxI; $i++) {

            if ($i < $colorCount) {
                $color = $this->colorIndexes[$i];
            } else {
                $color = 0;
            }

            $result .= Math::integer_2_24bit($color);
        }

        return $result;
    }
}