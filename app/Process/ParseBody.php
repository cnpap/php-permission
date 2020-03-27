<?php

namespace App\Process;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ParseBody implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['PUT', 'POST', 'DELETE'])) 
        {
            $contents = $request->getBody()->getContents();
            preg_replace_callback_array([
                '@application/x-www-form-urlencoded@' => function ($match) use (&$request, $contents) 
                {
                    parse_str($contents, $post);
                    $request = $request->withParsedBody($post);
                    return;
                },
                '@application/json@' => function ($match) use (&$request, $contents) 
                {
                    $post = json_decode($contents, true);
                    $request = $request->withParsedBody($post);
                    return;
                },
                '@text/plain@' => function ($match) use (&$request, $contents)
                {
                    $post = json_decode($contents, true);
                    $request = $request->withParsedBody($post);
                    return;
                }
            ], $request->getHeaderLine('Content-Type'));
        }

        return $handler->handle($request);
    }
}