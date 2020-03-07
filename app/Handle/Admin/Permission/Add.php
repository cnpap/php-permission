<?php

namespace App\Handle\Admin\Permission;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminPermission;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Psr7\Response;
use Suolong\Validator\Validator;

class Add implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();

        Validator::validate($post, [
            'menu'   => 'must&&int&&intIn:' . implode(',', [STATUS['YES'], STATUS['NO']]),
            'path'   => 'must&&string&&stringMax:100',
            'name'   => 'must&&string&&safe&&stringBetween:4,40',
            'memo'   => 'must&&string&&stringMax:200',
            'status' => 'must&&int&&intIn:' . implode(',', STATUS),
            'icon'   => 'must&&string&&safe&&stringMax:20'
        ]);

        $post['code']            = uniqid(time());
        $adminPermission         = new AdminPermission;
        $adminPermission->path   = $post['path'];
        $adminPermission->icon   = $post['icon'];
        $adminPermission->code   = $post['code'];
        $adminPermission->name   = $post['name'];
        $adminPermission->memo   = $post['memo'];
        $adminPermission->status = $post['status'];
        $save                    = $adminPermission->save();

        if ($save !== true) {
            throw new ExceptionBase('添加管理员权限失败');
        }

        return new Response;
    }
}