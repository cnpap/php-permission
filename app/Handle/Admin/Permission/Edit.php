<?php

namespace App\Handle\Admin\Permission;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminPermission;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Suolong\Validator\Validator;

class Edit implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();

        Validator::validate($post, [
            'parent_code' => 'string&&safe&&stringMax:40',
            'code'        => 'must&&string&&safe&&stringMax:40',
            'menu'        => 'must&&int&&intIn:' . implode(',', [STATUS['Yes'], STATUS['No']]),
            'path'        => 'must&&string&&stringMax:100',
            'name'        => 'must&&string&&safe&&stringBetween:4,40',
            'memo'        => 'must&&string&&stringMax:200',
            'status'      => 'must&&int&&intIn:' . implode(',', [STATUS['Yes'], STATUS['No']]),
            'icon'        => 'must&&string&&safe&&stringMax:20'
        ]);

        /** @var AdminPermission */
        $adminPermission              = AdminPermission::query()->findOrFail($post['code']);
        $adminPermission->path        = $post['path'];
        $adminPermission->icon        = $post['icon'];
        $adminPermission->code        = $post['code'];
        $adminPermission->name        = $post['name'];
        $adminPermission->memo        = $post['memo'];
        $adminPermission->menu        = $post['menu'];
        $adminPermission->status      = $post['status'];
        $adminPermission->parent_code = $post['parent_code'];
        $save                         = $adminPermission->save();

        if ($save !== true) {
            throw new ExceptionBase('修改管理员权限失败');
        }

        return new Response;
    }
}