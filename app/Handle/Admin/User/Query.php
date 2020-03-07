<?php

namespace App\Handle\Admin\User;

use App\Model\Admin\AdminUser;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Suolong\Validator\Validator;

class Query implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request): ResponseInterface
    {
        $get = $_GET;

        Validator::validate($get, [
            'page'     => 'int',
            'pre_page' => 'int&&intMax:50',
            'code'     => 'string&&safe&&stringMax:40',
            'username' => 'string&&safe&&stringMax:11',
            'name'     => 'string&&safe&&stringMax:40',
            'status'   => 'must&&array&&arrayMax:3',
            'status.*' => 'int&&intIn:' . implode(',', STATUS)
        ]);

        $get['code']     = $get['code'] ?? '';
        $get['username'] = $get['username'] ?? '';
        $get['name']     = $get['name'] ?? '';

        $data = AdminUser::query()
        ->where('code',     'like', '%' . $get['code']     . '%')
        ->where('username', 'like', '%' . $get['username'] . '%')
        ->where('name',     'like', '%' . $get['name']     . '%')
        ->paginate(
            $get['pre_page'] ?? 10,
            [
                'code',
                'username',
                'name',
                'memo',
                'status',
                'created_at',
                'updated_at'
            ],
            'page',
            $get['page'] ?? 1
        );

        return new Response(
            200,
            ['Content-Type' => ['application/json']],
            json_encode($data)
        );
    }
}