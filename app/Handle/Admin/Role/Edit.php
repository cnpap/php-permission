<?php

namespace App\Handle\Admin\Role;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminModel;
use App\Model\Admin\AdminRole;
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
            'code'              => 'must&&string&&safe&&stringMax:40',
            'username'          => 'must&&string&&phone',
            'name'              => 'must&&string&&safe&&stringBetween:4,40',
            'memo'              => 'must&&string&&stringMax:200',
            'status'            => 'must&&int&&intIn:' . implode(',', STATUS),
            'permission_code'   => 'must&&array&&arrayMax:40',
            'permission_code.*' => 'string&&safe&&stringMax:40'
        ]);

        (new AdminModel)->getConnection()->transaction(
            function () use ($post) {
                /** @var AdminRole */
                $adminRole           = AdminRole::query()->findOrFail($post['code']);
                $adminRole->username = $post['username'];
                $adminRole->name     = $post['name'];
                $adminRole->memo     = $post['memo'];
                $adminRole->status   = $post['status'];
                $save                = $adminRole->save();

                if ($save !== true) {
                    throw new ExceptionBase('修改管理员角色失败');
                }

                $sync = $adminRole->permissions()->sync($post['permission_code']);

                if (count($sync) < 1) {
                    throw new ExceptionBase('关联管理员权限失败');
                }
            }
        );

        return new Response;
    }
}