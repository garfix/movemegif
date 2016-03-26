<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class Formatter
{
    /**
     * Returns the lowest 24 bits of an integer as a string of 3 bytes.
     * @param int $number
     * @return string
     */
    public static function integer_2_24bit($number)
    {
        return chr($number >> 16) . chr($number >> 8) . chr($number);
    }

    /**
     * Converts an string of bytes into a space separated string of hex codes.
     *
     * @param $byteString
     * @return string
     */
    public static function byteString2hexString($byteString)
    {
        $hexString = '';
        for ($i = 0; $i < strlen($byteString); $i++) {
            $hexString .= ($hexString ? ' ' : '') . bin2hex($byteString[$i]);
        }
        $hexString = strtoupper($hexString);
        return $hexString;
    }
}