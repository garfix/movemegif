<?php

namespace movemegif\data;

/**
 * This class represents a sequence of codes (integers), stored in a string of bytes,
 * as compressed values (smaller codes take up less bits).
 *
 * @author Patrick van Bergen
 */
class CompressedCodeString
{
    private $bytes = '';
    private $byte = 0;
    private $powerOfTwo = 1;

    private $startBitsPerPixel;
    private $runningBits;

    private $startRunningCode;
    private $runningCode;
    private $maxCode;

    public function __construct($startBitsPerPixel, $startRunningCode)
    {
        $this->startBitsPerPixel = $startBitsPerPixel;
        $this->runningBits = $startBitsPerPixel + 1;

        $this->startRunningCode = $startRunningCode;
        $this->runningCode = $this->startRunningCode;
        $this->maxCode = 1 << $this->runningBits;
    }

    public function addCode($bits)
    {
        for ($b = 0; $b < $this->runningBits; $b++) {

            if ($this->powerOfTwo == 256) {

                // byte full
                $this->bytes .= chr($this->byte);

                // new byte
                $this->byte = 0;

                // back to rightmost bit
                $this->powerOfTwo = 1;
            }

            // rightmost bit of code = 1?
            if ($bits & 1) {

                // add it to result
                $this->byte += $this->powerOfTwo;
            }

            $bits >>= 1;
            $this->powerOfTwo <<= 1;
        }

        // increase number bits if necessary
        if ($this->runningCode >= $this->maxCode) {
            $this->runningBits++;
            $this->maxCode = 1 << $this->runningBits;
        }
    }

    public function getRunningCode()
    {
        return $this->runningCode;
    }

    public function incRunningCode()
    {
        $this->runningCode++;
    }

    public function reset()
    {
        $this->runningCode = $this->startRunningCode;
        $this->runningBits = $this->startBitsPerPixel + 1;
        $this->maxCode = 1 << $this->runningBits;
    }

    public function flush()
    {
        if ($this->powerOfTwo > 1) {
            $this->powerOfTwo = 0;
            $this->bytes .= chr($this->byte);
        }
    }

    public function getByteString()
    {
        return $this->bytes;
    }
}