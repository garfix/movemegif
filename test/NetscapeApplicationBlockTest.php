<?php

use movemegif\data\NetscapeApplicationBlock;

/**
 * @author Patrick van Bergen
 */
class NetscapeApplicationBlockTest extends PHPUnit_Framework_TestCase
{
    public function testNAB()
    {
        require_once __DIR__ . '/../php/autoloader.php';

        $block = new NetscapeApplicationBlock();
        $block->setRepeatCount(5);

        $contents = $block->getContents();

        $actual = '';
        for ($i = 0; $i < strlen($contents); $i++) {
            $actual .= bin2hex($contents[$i]) . ' ';
        }
        $actual = strtoupper($actual);

        $expected = "21 FF 0B 4E 45 54 53 43 41 50 45 32 2E 30 03 01 05 00 00 ";

        $this->assertEquals($expected, $actual);
    }
}