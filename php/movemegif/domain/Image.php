<?php

namespace movemegif\domain;

/**
 * @author Patrick van Bergen
 */
class Image
{
    private $data = null;

    public function setData()
    {

    }

    public function getData()
    {
        if ($this->data === null) {
            return "1 1 1 1 1 2 2 2 2 2 " .
            "1 1 1 1 1 2 2 2 2 2 " .
            "1 1 1 1 1 2 2 2 2 2 " .
            "1 1 1 0 0 0 0 2 2 2 " .
            "1 1 1 0 0 0 0 2 2 2 " .
            "2 2 2 0 0 0 0 1 1 1 " .
            "2 2 2 0 0 0 0 1 1 1 " .
            "2 2 2 2 2 1 1 1 1 1 " .
            "2 2 2 2 2 1 1 1 1 1 " .
            "2 2 2 2 2 1 1 1 1 1";

        } else {
            return $this->data;
        }
    }
}