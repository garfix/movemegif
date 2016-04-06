<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class ImageData
{
    const NUMBER_OF_SPECIAL_CODES = 2;
    const MAX_DICTIONARY_SIZE = 4096;

    /** @var int[] An array of color indexes */
    private $pixelColorIndexes;

    /** @var  int Number of colors in the table (a power of two) */
    private $colorTableSize;

    public function __construct(array $pixelColorIndexes, $colorTableSize)
    {
        $this->pixelColorIndexes = $pixelColorIndexes;
        $this->colorTableSize = $colorTableSize;
    }

    public function getContents()
    {
        /** @var int $lzwMinimumCodeSize The number of bits required for the initial color index codes, plus 2 special codes (Clear Code and End of Information Code) */
        $lzwMinimumCodeSize = $this->getMinimumCodeSize($this->colorTableSize);

        $codes = $this->compressCodes($this->gifLzwCompress(implode('', array_map('chr', $this->pixelColorIndexes)), $this->colorTableSize), $this->colorTableSize);

        return chr($lzwMinimumCodeSize) . DataSubBlock::createBlocks($codes) . DataSubBlock::createBlocks('');
    }

    /**
     * @param string $uncompressedString
     * @param int $colorIndexCount A power of two.
     * @return array
     */
    function gifLzwCompress($uncompressedString, $colorIndexCount)
    {
        // the resulting compressed string
        $resultCodes = array();

        // initialize sequence 2 code map
        list($sequence2code, $dictSize) = $this->createSequence2CodeMap($colorIndexCount);

        // define control codes
        $clearCode = $dictSize++;
        $endOfInformationCode = $dictSize++;

        // save the initial map
        $savedMap = $sequence2code;
        $savedDictSize = $dictSize;

        // start with a clear code
        $resultCodes[] = $clearCode;

$passed = false;
$q = 0;

        $previousSequence = "";
        $byteCount = strlen($uncompressedString);
        for ($i = 0; $i < $byteCount; $i++) {

            $colorIndex = $uncompressedString[$i];
$colorIndex = ($colorIndex === chr(0) ? 'NUL' : $colorIndex);
            $sequence = $previousSequence . $colorIndex;

            if (isset($sequence2code[$sequence])){//} array_key_exists($sequence, $sequence2code)) {

                // sequence found, next run, try to find an even longer sequence
                $previousSequence .= $colorIndex;

            } else {

if (0){//$passed) {
//if (1) {
    //$resultCodes[] = $sequence2code[$q ? chr(200) : chr(305)];
//    $resultCodes[] = $sequence2code[$q ? chr(200) : 'NUL'];
    //$resultCodes[] = $q ? 0 : 10;
    $resultCodes[] = $q ? 0 : 263;
    $q = $q ? 0 : 1;
} else {
                // this sequence was not found, store the longest sequence found to the result
                $resultCodes[] = $sequence2code[$previousSequence];
}
                // store the new sequence to the map
                $sequence2code[$sequence] = $dictSize++;

                // start a new sequence
                $previousSequence = $colorIndex;

                // the dictionary may hold only 2^12 items
                if ($dictSize == self::MAX_DICTIONARY_SIZE) {

$passed = true;

                    // reset the dictionary
                    $sequence2code = $savedMap;
                    $dictSize = $savedDictSize;
                    $previousSequence = '';

                    // insert a clear code
                    $resultCodes[] = $clearCode;
                }
            }
        }

        if ($previousSequence !== "") {
            $resultCodes[] = $sequence2code[$previousSequence];
        }

        // end with the end of information code
        $resultCodes[] = $endOfInformationCode;

        return $resultCodes;
    }

    /**
     * @param int $colorIndexCount A power of two
     * @return array
     */
    private function createSequence2CodeMap($colorIndexCount)
    {
        // a map of color index sequences to special codes
        $sequence2code = array();

        $dictSize = 0;

        // fill up the map with entries up to a power of 2
        for ($colorIndex = 0; $colorIndex < $colorIndexCount; $colorIndex++) {
            $sequence2code[$colorIndex == 0 ? 'NUL' : chr($colorIndex)] = $dictSize++;
//            $sequence2code[chr($colorIndex)] = $dictSize++;
        }

        return array($sequence2code, $dictSize);
    }

    /**
     * @param array $codes
     * @param int $colorCount A power of two.
     * @return string
     */
    private function compressCodes(array $codes, $colorCount)
    {
        $compressedBytes = new CompressedByteString();

        $bitsPerPixel = $this->getMinimumCodeSize($colorCount); // 8

        $startRunningCode = Math::firstPowerOfTwo($colorCount) ; //$startRunningCode = 256;
        $runningBits = $bitsPerPixel + 1; // bits per pixel + 1
        $runningCode = $startRunningCode;
        $maxCode1 = 1 << $runningBits;

        foreach ($codes as $i => $code) {

            $compressedBytes->bla($code, $runningBits);

            // increase code size
            $runningCode++;
            if ($runningCode >= $maxCode1) {
                $runningBits++;
                $maxCode1 = 1 << $runningBits;
            }

            if ($code == 256 && $i != 0) {
            //if ($runningCode >= 4095) {
                $runningCode = $startRunningCode;
                $runningBits = $bitsPerPixel + 1;
                $maxCode1 = 1 << $runningBits;

            }
        }

        $bytes = $compressedBytes->getByteString();

        return $bytes;
    }

    private function getMinimumCodeSize($colorCount)
    {
        // The GIF spec requires a minimum of 2
        return max(2, Math::minimumBits($colorCount));
    }
}