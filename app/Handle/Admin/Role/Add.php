<?php

namespace App\Handle\Admin\Role;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminModel;
use App\Model\Admin\AdminRole;
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
            'name'              => 'must&&string&&safe&&stringBetween:4,40',
            'memo'              => 'must&&string&&stringMax:200',
            'status'            => 'must&&int&&intIn:' . implode(',', STATUS),
            'permission_code'   => 'array&&arrayMax:40',
            'permission_code.*' => 'string&&safe&&stringMax:40'
        ]);

        $post['code'] = uniqid(time());

        (new AdminModel)->getConnection()->transaction(
            function () use ($post) {
                $adminRole         = new AdminRole;
                $adminRole->code   = $post['code'];
                $adminRole->name   = $post['name'];
                $adminRole->memo   = $post['memo'];
                $adminRole->status = $post['status'];
                $save = $adminRole->save();

                if ($save !== true) {
                    throw new ExceptionBase('添加管理员角色失败');
                }

                $sync = $adminRole->permissions()->sync($post['permission_code']);

                if (count($sync) < 1) 
                {
                    throw new ExceptionBase('关联管理员权限失败');
                }
            }
        );

        return new Response;
    }
}