<?php

namespace movemegif;

use movemegif\data\ApplicationExtension;
use movemegif\data\GlobalColorTable;
use movemegif\data\GraphicExtension;
use movemegif\data\HeaderBlock;
use movemegif\data\LogicalScreenDescriptor;
use movemegif\data\NetscapeApplicationBlock;
use movemegif\data\Trailer;
use movemegif\domain\Image;
use movemegif\domain\Repeat;

/**
 * @author Patrick van Bergen
 */
class GifBuilder
{
    private $extensions = array();

    /**
     * @return Image
     */
    public function addImage()
    {
        $image = new Image();
        $this->extensions[] = $image;
        return $image;
    }

    /**
     * @return Image
     */
    public function addRepeat()
    {
        $repeat = new Repeat();
        $this->extensions[] = $repeat;
        return $repeat;
    }

    public function getContents()
    {
        $logicalScreenDescriptor = new LogicalScreenDescriptor();
        $headerBlock = new HeaderBlock();
        $globalColorTable = new GlobalColorTable();
        $trailer = new Trailer();

        $extensionContents = '';

        foreach ($this->extensions as $extension) {
            if ($extension instanceof Image) {
                $ext = new GraphicExtension();
            } elseif ($extension instanceof Repeat) {
                $ext = new NetscapeApplicationBlock();
                $ext->setRepeatCount($extension->getTimes());
            } else {
#todo error
            }
            $extensionContents .= $ext->getContents();
        }

        return
            $headerBlock->getContents() .
            $logicalScreenDescriptor->getContents() .
            $globalColorTable->getContents() .
            $extensionContents .
            $trailer->getContents();
    }

    public function output()
    {
        header('Content-type: image/gif');
        header('Content-disposition: inline; filename="name.gif"');

        echo $this->getContents();
    }
}