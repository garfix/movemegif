<?php

namespace movemegif;

use movemegif\data\Clipper;
use movemegif\data\ColorTable;
use movemegif\data\CommentExtension;
use movemegif\data\Extension;
use movemegif\data\GDAcceleratedPixelDataProducer;
use movemegif\data\GraphicExtension;
use movemegif\data\HeaderBlock;
use movemegif\data\LogicalScreenDescriptor;
use movemegif\data\NetscapeApplicationBlock;
use movemegif\data\PHPPixelDataProducer;
use movemegif\data\Trailer;
use movemegif\domain\ClippingArea;
use movemegif\domain\Frame;
use movemegif\domain\GdCanvas;
use movemegif\exception\MovemegifException;

/**
 * @author Patrick van Bergen
 */
class GifBuilder
{
    const MOVEMEGIF_SIGNATURE = "Created with movemegif";

    /** @var Extension[] */
    private $extensions = array();

    /** @var string[] */
    private $comments = array();

    /** @var int The number of times all frames must be repeated */
    private $repeat = null;

    /** @var int A 0x00RRGGBB representation of a color. Not used by most browsers, nor by this lib */
    private $backgroundColor = null;

    /** @var  int Width of the canvas in [0..65535] */
    private $width;

    /** @var  int Height of the canvas in [0..65535] */
    private $height;

    /**
     * Creates the builder with the width and height of the image canvas.
     * When width and height are left empty, they will be taken from the first frame.
     *
     * @param int|null $width
     * @param int|null $height
     */
    public function __construct($width = null, $height = null)
    {
        $this->width = $width;
        $this->height = $height;
    }

    private function getWidth()
    {
        if ($this->width === null) {
            $this->initializeDimensionsToFirstFrame();
        }
        return $this->width;
    }

    private function getHeight()
    {
        if ($this->height === null) {
            $this->initializeDimensionsToFirstFrame();
        }
        return $this->height;
    }

    private function initializeDimensionsToFirstFrame()
    {
        foreach ($this->extensions as $extension) {
            if ($extension instanceof Frame) {
                $this->width = $extension->getWidth();
                $this->height = $extension->getHeight();
                break;
            }
        }
    }

    /**
     * @return Frame
     */
    public function addFrame()
    {
        $frame = new Frame();
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

    /**
     * Adds a commenting text to the file. This comment serves mainly as a signature of the creator,
     *
     * @param string $comment
     */
    public function addComment($comment)
    {
        $this->comments[] = $comment;
    }

    /**
     * Returns a string of bytes forming an animated GIF image (GIF 89a).
     *
     * @return string
     * @throws MovemegifException
     */
    public function getContents()
    {
        $clipper = new Clipper();
        $globalColorTable = new ColorTable(false);
        $headerBlock = new HeaderBlock();
        $trailer = new Trailer();
        $canvasWidth = $this->getWidth();
        $canvasHeight = $this->getHeight();

        $extensionContents = '';

        if ($this->repeat !== null) {

            // the repeat-block causes all other frames to be repeated
            // its position in the file has no effect on its function

            $repeat = new NetscapeApplicationBlock();
            $repeat->setRepeatCount($this->repeat);

            $extensionContents .= $repeat->getContents();
        }

        foreach ($this->extensions as $extension) {

            if ($extension instanceof Frame) {

                $frame = $extension;

                if ($frame->usesLocalColorTable()) {
                    $colorTable = new ColorTable(true);
                } else {
                    $colorTable = $globalColorTable;
                }

                // the clipping area itself needs to be clipped along the borders of the frame and the whole image
                $clip = $clipper->getClip($frame, $canvasWidth, $canvasHeight);

                // select the fast pixel data generator if possible
                $pixelDataProducer = $this->getPixelDataProducer($frame, $clip, $colorTable);

                $graphic = new GraphicExtension(
                    $pixelDataProducer,
                    $colorTable,
                    $frame->getDuration(),
                    $frame->getDisposalMethod(),
                    $frame->getTransparencyColor(),
                    $clip->getRight() - $clip->getLeft() + 1,
                    $clip->getBottom() - $clip->getTop() + 1,
                    $frame->getLeft() + $clip->getLeft(),
                    $frame->getTop() + $clip->getTop()
                );

                $extensionContents .= $graphic->getContents();
            }
        }

        // comments
        $comments = $this->comments;

        // prepend our signature
        if (array_search(self::MOVEMEGIF_SIGNATURE, $comments) === false) {
            array_unshift($comments, self::MOVEMEGIF_SIGNATURE);
        }

        foreach ($comments as $comment) {
            $extension = new CommentExtension($comment);
            $extensionContents .= $extension->getContents();
        }

        $logicalScreenDescriptor = new LogicalScreenDescriptor($canvasWidth, $canvasHeight, $globalColorTable, $this->backgroundColor);

        return
            $headerBlock->getContents() .
            $logicalScreenDescriptor->getContents() .
            $globalColorTable->getContents() .
            $extensionContents .
            $trailer->getContents();
    }

    private function getPixelDataProducer(Frame $frame, ClippingArea $clip, ColorTable $colorTable)
    {
        if (
            $frame->usesLocalColorTable() &&
            $frame->getCanvas() instanceof GdCanvas
        ) {

            /** @var GdCanvas $gdCanvas */
            $gdCanvas = $frame->getCanvas();

            $pixelDataProducer = new GDAcceleratedPixelDataProducer(
                $gdCanvas,
                $clip
            );

        } else {

            $pixelDataProducer = new PHPPixelDataProducer(
                $frame->getPixels($clip->getLeft(), $clip->getTop(), $clip->getRight(), $clip->getBottom()),
                $colorTable
            );

        }

        return $pixelDataProducer;
    }

    /**
     * Writes
     * Outputs a string of bytes forming an animated GIF image (GIF 89a).
     *
     * @param string $fileName
     * @return string
     */
    public function output($fileName = 'moveme.gif')
    {
        // get contents _before_ writing headers, so that any exceptions are shown properly
        $contents = $this->getContents();

        header('Content-type: image/gif');
        header('Content-disposition: inline; filename="' . $fileName . '"');

        echo $contents;
    }

    /**
     * Creates a GIF image file from the present information.
     *
     * @param string $filePath
     */
    public function saveToFile($filePath)
    {
        file_put_contents($filePath, $this->getContents());
    }
}