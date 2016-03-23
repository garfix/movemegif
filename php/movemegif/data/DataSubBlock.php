<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class DataSubBlock
{
    public static function createBlocks($bytes)
    {
        $dataSubBlocks = '';
        foreach (str_split($bytes, 255) as $block) {
            $dataSubBlocks .= chr(strlen($block)) . $block;
        }

        return $dataSubBlocks;
    }
}