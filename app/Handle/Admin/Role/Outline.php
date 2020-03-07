<?php

namespace App\Handle\Admin\Role;

use App\Model\Admin\AdminRole;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Outline implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request): ResponseInterface
    {
        $outline = AdminRole::query()
        ->get([
            'code',
            'name'
        ]);

        return new Response(
            200, 
            ['Content-Type' => ['application/json']], 
            json_encode($outline)
        );
    }
}