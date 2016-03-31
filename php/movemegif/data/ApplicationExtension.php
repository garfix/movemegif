<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
abstract class ApplicationExtension implements Extension
{
    const APPLICATION_EXTENSION_LABEL = 0xFF;
    const APP_START = 1;

    protected $applicationIdentifier = '12345678';
    protected $authenticationCode = '1.0';

    public function getContents()
    {
        return
            chr(self::EXTENSION_INTRODUCER) . chr(self::APPLICATION_EXTENSION_LABEL) .
            DataSubBlock::createBlocks($this->applicationIdentifier . $this->authenticationCode) .
            DataSubBlock::createBlocks(chr(self::APP_START) . $this->getApplicationData()) .
            DataSubBlock::createBlocks('');
    }

    protected abstract function getApplicationData();
}