<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
interface Extension
{
    const EXTENSION_INTRODUCER = 0x21;

    public function getContents();
}