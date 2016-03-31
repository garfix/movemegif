<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class CommentExtension implements Extension
{
    const COMMENT_CONTROL_LABEL = 0xFE;

    private $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function getContents()
    {
        return
            chr(self::EXTENSION_INTRODUCER) . chr(self::COMMENT_CONTROL_LABEL) .
            DataSubBlock::createBlocks($this->comment) .
            DataSubBlock::createBlocks('');
    }
}