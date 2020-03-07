<?php

namespace App\Handle\Admin\Role;

use App\Model\Admin\AdminRole;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Suolong\Validator\Validator;

class Find implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request): ResponseInterface
    {
        Validator::validate($_GET, [
            'code' => 'must&&string&&safe&&stringMax:40'
        ]);

        $user = AdminRole::query()
        ->findOrFail(
            $_GET['code'],
            [
                'code',
                'username',
                'name',
                'memo',
                'status',
                'created_at',
                'updated_at'
            ]
        );

        return new Response(
            200, 
            ['Content-Type' => ['application/json']],
            json_encode($user)
        );
    }
}