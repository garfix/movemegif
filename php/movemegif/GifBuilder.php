<?php

namespace movemegif;

use movemegif\data\ColorTable;
use movemegif\data\Extension;
use movemegif\data\GraphicExtension;
use movemegif\data\HeaderBlock;
use movemegif\data\LogicalScreenDescriptor;
use movemegif\data\NetscapeApplicationBlock;
use movemegif\data\Trailer;
use movemegif\domain\Frame;

/**
 * @author Patrick van Bergen
 */
class GifBuilder
{
    /** @var Extension[] */
    private $extensions = array();

    /** @var int The number of times all frames must be repeated */
    private $repeat = null;

    /** @var  int Width of the canvas in [0..65535] */
    private $width;

    /** @var  int Height of the canvas in [0..65535] */
    private $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @param $width
     * @param $height
     * @param int $left
     * @param int $top
     * @return Frame
     */
    public function addFrame($width, $height, $left = 0, $top = 0)
    {
        $frame = new Frame($width, $height, $left, $top);
        $this->extensions[] = $frame;
        return $frame;
    }

    /**
     * The number of times the frames should be repeated (0 = loop forever).
     *
     * Note: clients are known to interpret this differently.
     * At the time of writing, Chrome interprets $nTimes = 2 as 2 times on top of the 1 time it normally plays.
     * For Firefox, $nTimes = 2 means: play 2 times.
     *
     * @param int $nTimes
     */
    public function setRepeat($nTimes = 0)
    {
        $this->repeat = $nTimes;
    }

    public function getContents()
    {
        $globalColorTable = new ColorTable(false);
        $headerBlock = new HeaderBlock();
        $trailer = new Trailer();

        $extensionContents = '';

        if ($this->repeat !== null) {

            $repeat = new NetscapeApplicationBlock();
            $repeat->setRepeatCount($this->repeat);

            $extensionContents .= $repeat->getContents();
        }

        foreach ($this->extensions as $extension) {

            if ($extension instanceof Frame) {

                if ($extension->usesLocalColorTable()) {
                    $colorTable = new ColorTable(true);
                } else {
                    $colorTable = $globalColorTable;
                }

                $graphic = new GraphicExtension(
                    $extension->getPixels(),
                    $colorTable,
                    $extension->getduration(),
                    $extension->getWidth(),
                    $extension->getHeight(),
                    $extension->getLeft(),
                    $extension->getTop()
                );

                $extensionContents .= $graphic->getContents();
            }
        }

        $logicalScreenDescriptor = new LogicalScreenDescriptor($this->width, $this->height, $globalColorTable);

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