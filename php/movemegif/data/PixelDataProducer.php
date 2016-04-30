<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
interface PixelDataProducer
{
    /**
     * Returns a string of GIF LWZ compressed data for the pixels of a frame.
     *
     * @return string
     */
    public function getCompressedPixelData();

    /**
     * Returns color data as a string of triples of RGB bytes.
     *
     * @return string
     */
    public function getColorData();

    /**
     * @return int
     */
    public function getColorTableSize();
}