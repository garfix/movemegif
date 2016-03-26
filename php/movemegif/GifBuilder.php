<?php

namespace movemegif;

use movemegif\data\ApplicationExtension;
use movemegif\data\ColorTable;
use movemegif\data\GraphicExtension;
use movemegif\data\HeaderBlock;
use movemegif\data\LogicalScreenDescriptor;
use movemegif\data\NetscapeApplicationBlock;
use movemegif\data\Trailer;
use movemegif\domain\Frame;
use movemegif\domain\Repeat;

/**
 * @author Patrick van Bergen
 */
class GifBuilder
{
    private $extensions = array();

    /**
     * @return Frame
     */
    public function addFrame()
    {
        $image = new Frame();
        $this->extensions[] = $image;
        return $image;
    }

    /**
     * @return Frame
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
        $globalColorTable = new ColorTable(false);
        $trailer = new Trailer();

        $extensionContents = '';

        foreach ($this->extensions as $extension) {
            if ($extension instanceof Frame) {

                if ($extension->usesLocalColorTable()) {
                    $colorTable = new ColorTable(true);
                } else {
                    $colorTable = $globalColorTable;
                }

                $graphic = new GraphicExtension($extension->getPixels(), $colorTable);
                $graphic->setDuration($extension->getduration());

                $extensionContents .= $graphic->getContents();

            } elseif ($extension instanceof Repeat) {

                $repeat = new NetscapeApplicationBlock();
                $repeat->setRepeatCount($extension->getTimes());

                $extensionContents .= $repeat->getContents();

            } else {
#todo error
            }

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