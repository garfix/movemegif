<?php

namespace movemegif\data;
use movemegif\exception\TooManyColorsException;

/**
 * @author Patrick van Bergen
 */
class ColorTable
{
    /** @var  bool Is this table linked to a single frame? */
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

    /**
     * @param $color
     * @return int An index
     * @throws TooManyColorsException
     */
    public function getColorIndex($color)
    {
        $key = array_search($color, $this->colorIndexes);

        if ($key === false) {
            $key = count($this->colorIndexes);
            if ($key == 256) {
                throw TooManyColorsException::create($this->isLocal());
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
        // The GIF spec requires a minimum of 4
        return max(4, Math::firstPowerOfTwo(count($this->colorIndexes)));
    }

    public function getContents()
    {
        $result = '';
        $colorCount = count($this->colorIndexes);
        $maxI = $this->getTableSize();

        for ($i = 0; $i < $maxI; $i++) {

            if ($i < $colorCount) {
                $color = $this->colorIndexes[$i];
            } else {
                $color = 0;
            }

            $result .= Formatter::integer_2_24bit($color);
        }

        return $result;
    }
}