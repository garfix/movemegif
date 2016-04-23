<?php

namespace movemegif\domain;

use movemegif\exception\MovemegifException;

/**
 * @author Patrick van Bergen
 */
class FileImageCanvas extends GdCanvas
{
    public function __construct($filePath)
    {
        $resource = null;

        if (preg_match('#\.(gif|png|jpg|jpeg)$#', $filePath, $matches)) {

            $ext = $matches[1];

            switch ($ext) {

                case 'gif':
                    $resource = imagecreatefromgif($filePath);
                    break;

                case 'png':
                    $resource = imagecreatefrompng($filePath);
                    break;

                case 'jpg':
                case 'jpeg':
                    $resource = imagecreatefromjpeg($filePath);
                    break;

            }

        } else {
            throw new MovemegifException('Unknown filetype. Choose one of gif, png or jpg.');
        }

        if (!$resource) {
            throw new MovemegifException('Unable to read file: ' . $filePath);
        }

        $width = imagesx($resource);
        $height = imagesy($resource);

        parent::__construct($width, $height);

        imagecopy($this->resource, $resource, 0, 0, 0, 0, $width, $height);
    }
}