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

        $splitedString = str_split($bytes, 255);
        foreach ($splitedString as $block) {
            $dataSubBlocks .= chr(strlen($block)) . $block;
        }
        
        //With php >=8.2.0 str_split() return a empty array if the parameter is a empty string.
        //Add dataSubBlock for empty String
        if(count($splitedString) == 0) {
            $dataSubBlocks .= chr(0);
            
        }

        return $dataSubBlocks;
    }
}
