<?php

namespace App\Handle\Auth;

use App\Model\Admin\AdminUser;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Suolong\Validator\Validator;

class Password implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();
        $user = $request->getAttribute('user');

        Validator::validate($post, [
            'password'     => 'must&&string&&safe&&stringBetween:6,16',
            'new_password' => 'must&&string&&safe&&stringBetween:6,16'
        ]);

        /** @var AdminUser */
        $adminUser = AdminUser::query()
        ->where('code',     $user['code'])
        ->where('password', md5($post['password']))
        ->first();

        if ($adminUser === null) {
            throw new RuntimeException('没有找到该用户');
        }

        $adminUser->password = md5($post['new_password']);
        $save = $adminUser->save();

        if ($save !== true) {
            throw new RuntimeException('修改密码失败');
        }

        return new Response(200, [], '修改密码成功');
    }
}