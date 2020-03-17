<?php

namespace App\Process;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Psr7\Response;

class CheckAdmin implements MiddlewareInterface
{
    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $tokenHeader = $request->getHeaderLine('Authorization');

        if (!$tokenHeader) 
        {
            return new Response(401, [], '请登陆');
        }

        $tokenStack = explode(' ', $tokenHeader);

        if (count($tokenStack) !== 2 && count(explode('@', $tokenStack[1])) !== 2)
        {
            return new Response(403, [], '登陆凭据非法');
        }

        list($tokenPayload, $tokenCheck) = explode('@', $tokenStack[1]);
        $payload                         = json_decode(base64_decode($tokenPayload), true);

        if (!isset($payload['expired']) || (int) $payload['expired'] < time()) 
        {
            return new Response(403, [], '登陆凭据已过期');
        }

        $check = hash_hmac('sha256', $tokenPayload, CONF['app']['secret']);

        if ($tokenCheck !== $check) 
        {
            return new Response(403, [], '登陆凭据无法通过验证');
        }

        $request = $request->withAttribute('user', $payload);

        return $handler->handle($request);
    }
}