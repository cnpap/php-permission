<?php

namespace App\Process;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WithConf implements MiddlewareInterface
{
    function process(ServerRequestInterface $request, RequestHandlerInterface $handle): ResponseInterface
    {
        $request = $request->withAttribute('conf', CONF);

        return $handle->handle($request);
    }
}