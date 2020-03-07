<?php

namespace App\Exception\Base;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Psr7\Response;
use Exception;

class ExceptionProcess implements MiddlewareInterface
{
    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ExceptionBase $e) {
            return new Response(500, [], $e->getResponse());
        } catch (Exception $e) {
            throw $e;
        }
    }
}