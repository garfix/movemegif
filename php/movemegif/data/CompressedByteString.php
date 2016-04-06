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

    public function bla($bits, &$runningBits)
    {
        for ($b = 0; $b < $runningBits; $b++) {

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
    }

    public function getByteString()
    {

        if ($this->powerOfTwo > 1) {
            $this->bytes .= chr($this->byte);
        }

        return $this->bytes;
    }
}