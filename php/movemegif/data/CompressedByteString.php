<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class CompressedByteString
{
    private $bytes = '';
    private $byte = 0;
    private $powerOfTwo = 1;
    private $startRunningCode;
    private $bitsPerPixel;
    private $runningCode;
    private $runningBits;
    private $maxCode1;

    public function __construct($colorIndexCount)
    {
        $this->bitsPerPixel = $this->getMinimumCodeSize($colorIndexCount);
        $this->startRunningCode = Math::firstPowerOfTwo($colorIndexCount);

        $this->runningCode = $this->startRunningCode;
        $this->runningBits = $this->bitsPerPixel + 1;
        $this->runningCode = $this->startRunningCode;
        $this->maxCode1 = 1 << $this->runningBits;
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

// increase code size
        $this->runningCode++;
        if ($this->runningCode >= $this->maxCode1) {
            $this->runningBits++;
            $this->maxCode1 = 1 << $this->runningBits;
        }

    }

    public function flush()
    {
//if ($runningCode >= 4095) {
        $this->runningCode = $this->startRunningCode;
        $this->runningBits = $this->bitsPerPixel + 1;
        $this->maxCode1 = 1 << $this->runningBits;

    }

    public function getByteString()
    {

        if ($this->powerOfTwo > 1) {
            $this->bytes .= chr($this->byte);
        }

        return $this->bytes;
    }

    private function getMinimumCodeSize($colorCount)
    {
        // The GIF spec requires a minimum of 2
        return max(2, Math::minimumBits($colorCount));
    }
}