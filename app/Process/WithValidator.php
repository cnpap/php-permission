<?php

namespace App\Process;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Suolong\Validator\Validator;
use App\ValidateHandle;

class WithValidator implements MiddlewareInterface
{
    function process(ServerRequestInterface $requset, RequestHandlerInterface $handle) : ResponseInterface
    {
        Validator::$handle = new ValidateHandle;

        return $handle->handle($requset);
    }
}