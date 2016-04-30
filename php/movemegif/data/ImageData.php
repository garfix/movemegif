<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class ImageData
{
    const MAX_DICTIONARY_SIZE = 4095;

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
        $codes = $this->gifLzwCompress($this->pixelColorIndexes, $this->colorTableSize);

        return $codes;
    }

    /**
     * Turns $uncompressedString into a compressed string of codes.
     *
     * The exact implementation of this algorithm is very fragile, and It contains ideas and implementation details from both
     * http://www.matthewflickinger.com/lab/whatsinagif/lzw_image_data.asp and
     * https://sourceforge.net/p/giflib/code/ci/master/tree/lib/egif_lib.c
     *
     * @param array $colorIndexes
     * @param int $colorIndexCount Number of colors (the first power of two that spans it)
     * @return array
     */
    private function gifLzwCompress(array $colorIndexes, $colorIndexCount)
    {
        // initialize sequence 2 code map
        list($sequence2code, $runningCode) = $this->createSequence2CodeMap($colorIndexCount);

        // define control codes
        $clearCode = $runningCode++;
        $endOfInformationCode = $runningCode++;

        // save the initial map
        $savedMap = $sequence2code;
        $startBitsPerPixel = $this->getMinimumCodeSize($colorIndexCount);

        $compressedCodes = new CompressedCodeString($startBitsPerPixel, $runningCode);

        // start with a clear code
        $compressedCodes->addCode($clearCode);

        $previousSequence = "";
        $byteCount = count($colorIndexes);
        for ($i = 0; $i < $byteCount; $i++) {

            $colorIndex = chr($colorIndexes[$i]);
            $sequence = $previousSequence . $colorIndex;

            if (isset($sequence2code[$sequence])) {

                // sequence found, next run, try to find an even longer sequence
                $previousSequence .= $colorIndex;

            } else {

                // this sequence was not found, store the longest sequence found to the result
                $compressedCodes->addCode($sequence2code[$previousSequence]);

                // start a new sequence
                $previousSequence = $colorIndex;

                // the dictionary may hold only 2^12 items
                if ($compressedCodes->getRunningCode() >= self::MAX_DICTIONARY_SIZE) {

                    // insert a clear code
                    $compressedCodes->addCode($clearCode);

                    // reset the code and the number of bits representing them
                    $compressedCodes->reset();

                    // reset the dictionary
                    $sequence2code = $savedMap;

                } else {

                    // store the new sequence to the map
                    $sequence2code[$sequence] = $compressedCodes->getRunningCode();
                    $compressedCodes->incRunningCode();

                }
            }
        }

        // add the last code that is still in the pipeline
        if ($previousSequence !== "") {
            $compressedCodes->addCode($sequence2code[$previousSequence]);
        }

        // end with the end of information code
        $compressedCodes->addCode($endOfInformationCode);

        // write pending bits to the code string
        $compressedCodes->flush();

        return $compressedCodes->getByteString();
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
            $sequence2code[chr($colorIndex)] = $dictSize++;
        }

        return array($sequence2code, $dictSize);
    }

    private function getMinimumCodeSize($colorCount)
    {
        // The GIF spec requires a minimum of 2
        return max(2, Math::minimumBits($colorCount));
    }
}