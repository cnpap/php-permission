<?php

namespace App\Handle\Admin\User;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminModel;
use App\Model\Admin\AdminUser;
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
            'code'        => 'must&&string&&safe&&stringMax:40',
            'username'    => 'must&&string&&phone',
            'name'        => 'must&&string&&safe&&stringBetween:4,40',
            'memo'        => 'must&&string&&stringMax:200',
            'status'      => 'must&&int&&intIn:' . implode(',', STATUS),
            'role_code'   => 'must&&array&&arrayMax:40',
            'role_code.*' => 'string&&safe&&stringMax:40'
        ]);

        (new AdminModel)->getConnection()->transaction(
            function () use ($post) {
                /** @var AdminUser */
                $adminUser           = AdminUser::query()->findOrFail($post['code']);
                $adminUser->username = $post['username'];
                $adminUser->name     = $post['name'];
                $adminUser->memo     = $post['memo'];
                $adminUser->status   = $post['status'];
                $save                = $adminUser->save();

                if ($save !== true) {
                    throw new ExceptionBase('修改管理员用户失败');
                }

                $sync = $adminUser->roles()->sync($post['role_code']);

                if (count($sync) < 1) {
                    throw new ExceptionBase('关联管理员角色失败');
                }
            }
        );

        return new Response;
    }
}