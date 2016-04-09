<?php

use movemegif\data\Formatter;
use movemegif\data\NetscapeApplicationBlock;

require_once __DIR__ . '/../../php/autoloader.php';

/**
 * Tests Netscape's extension for looping.
 *
 * @author Patrick van Bergen
 */
class UnitTest extends PHPUnit_Framework_TestCase
{
    public function testNetscapeApplicationBlock()
    {
        $block = new NetscapeApplicationBlock();
        $block->setRepeatCount(5);

        $contents = $block->getContents();

        $actual = Formatter::byteString2hexString($contents);
        $expected = "21 FF 0B 4E 45 54 53 43 41 50 45 32 2E 30 03 01 05 00 00";

        $this->assertEquals($expected, $actual);
    }
}