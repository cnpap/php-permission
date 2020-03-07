<?php

namespace App\Handle\Admin\Permission;

use App\Model\Admin\AdminPermission;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Outline implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request): ResponseInterface
    {
        $outline = AdminPermission::query()
        ->get([
            'code',
            'name',
            'menu',
            'parent_code'
        ]);

        return new Response(
            200, 
            ['Content-Type' => ['application/json']], 
            json_encode($outline)
        );
    }
}