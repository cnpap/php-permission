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

        $role = AdminRole::query()
        ->findOrFail(
            $_GET['code'],
            [
                'code',
                'name',
                'memo',
                'status',
                'created_at',
                'updated_at'
            ]
        );

        $permissions = $role
        ->permissions()
        ->get(['code']);

        $role                    = $role->toArray();
        $role['permission_code'] = [];

        foreach ($permissions as $permission)
        {
            $role['permission_code'][] = $permission->code;
        }

        return new Response(
            200, 
            ['Content-Type' => ['application/json']],
            json_encode($role)
        );
    }
}