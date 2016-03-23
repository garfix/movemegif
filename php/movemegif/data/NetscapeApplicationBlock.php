<?php

namespace movemegif\data;

/**
 * @author Patrick van Bergen
 */
class NetscapeApplicationBlock extends ApplicationExtension
{
    private $repeatCount = 0;

    protected $applicationIdentifier = 'NETSCAPE';
    protected $authenticationCode = '2.0';

    public function setRepeatCount($repeatCount)
    {
        $this->repeatCount = $repeatCount;
    }

    protected function getApplicationData()
    {
        return pack('v', $this->repeatCount);
    }
}