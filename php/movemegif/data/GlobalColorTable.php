<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class GlobalColorTable
{
    public function getContents()
    {
        $a = "FF FF FF FF 00 00 00 00 FF 00 00 00";
        $result = '';

        foreach (explode(' ', $a) as $byte) {
            $result .= hex2bin($byte);
        }

        return $result;
    }
}