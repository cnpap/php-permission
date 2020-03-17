<?php

namespace App\Handle\Auth;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminUser;
use App\ValidateHandle;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Suolong\Validator\Validator;

class Login implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $post = $request->getParsedBody();

        Validator::validate($post, [
            'username' => 'must&&string&&phone',
            'password' => 'must&&string&&stringBetween:4,16'
        ]);

        $adminUser = AdminUser::query()
        ->where('username', $post['username'])
        ->where('password', md5($post['password']))
        ->first([
            'code',
            'username',
            'name',
            'memo',
            'status'
        ]);

        if ($adminUser === null) {
            throw new ExceptionBase('登陆失败, 用户名或密码不正确');
        }

        if ($adminUser['status'] !== STATUS['Yes'])
        {
            throw new ExceptionBase('该账户未启用');
        }

        $payload = [];
        $payload['code']     = $adminUser['code'];
        $payload['username'] = $adminUser['username'];
        $payload['name']     = $adminUser['name'];
        $payload['expired']  = time() + CONF['app']['expired'] ?? 7200;
        $payload             = base64_encode(json_encode($payload));
        $sign = hash_hmac('sha256', $payload, CONF['app']['secret'] ?? 'secret');
        $data = [
            'token' => $payload . '@' . $sign
        ];
        $data = json_encode($data);

        return new Response(200, ['Content-Type' => ['application/json']], $data);
    }
}