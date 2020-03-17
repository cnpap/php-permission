<?php

namespace App\Process;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Options implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        header('Access-Control-Allow-Origin: *');

        if ($request->getMethod() === 'OPTIONS') 
        {
            header('Access-Control-Allow-Headers: *');
            header('Access-Control-Allow-Methods: *');
            return new Response(200, [], 'ok');
        }

        return $handler->handle($request);
    }
}