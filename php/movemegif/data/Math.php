<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class Math
{
    /**
     * Returns the first power of 2 equal to or higher than $number.
     *
     * @param $number
     * @return int
     */
    public static function firstPowerOfTwo($number)
    {
        return pow(2, self::minimumBits($number));
    }

    /**
     * Returns the least number of bits that can hold $number.
     *
     * @param $colorCount
     * @return mixed
     */
    public static function minimumBits($colorCount)
    {
        $size = 0;
        $bits = $colorCount - 1;

        while ($bits > 0) {
            $size++;
            $bits >>= 1;
        }

        return $size;
    }

    /**
     * Returns the lowest 24 bits of an integer as a string of 3 bytes.
     * @param int $number
     * @return string
     */
    public static function integer_2_24bit($number)
    {
        return chr($number >> 16) . chr($number >> 8) . chr($number);
    }
}