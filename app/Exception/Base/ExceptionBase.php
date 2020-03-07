<?php

namespace App\Exception\Base;

use RuntimeException;

class ExceptionBase extends RuntimeException
{
    function getResponse()
    {
        return $this->message;
    }
}