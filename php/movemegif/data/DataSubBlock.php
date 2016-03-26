<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class DataSubBlock
{
    /**
     * Splits $bytes in byte blocks of at most 255 bytes. Each block is preceded by its length (a single byte).
     *
     * @param $bytes
     * @return string
     */
    public static function createBlocks($bytes)
    {
        $dataSubBlocks = '';

        foreach (str_split($bytes, 255) as $block) {
            $dataSubBlocks .= chr(strlen($block)) . $block;
        }

        return $dataSubBlocks;
    }
}