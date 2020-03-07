<?php

namespace App\Handle\Admin\Permission;

use App\Model\Admin\AdminPermission;
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
            'name'     => 'string&&safe&&stringMax:40',
            'status'   => 'must&&array&&arrayMax:3',
            'status.*' => 'int&&intIn:' . implode(',', STATUS)
        ]);

        $get['code'] = $get['code'] ?? '';
        $get['name'] = $get['name'] ?? '';

        $data = AdminPermission::query()
        ->where('code',     'like', '%' . $get['code']     . '%')
        ->where('name',     'like', '%' . $get['name']     . '%')
        ->whereIn('status', $get['status'])
        ->paginate(
            $get['pre_page'] ?? 10,
            [
                'code',
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