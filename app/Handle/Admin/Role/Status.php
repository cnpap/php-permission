<?php

namespace App\Handle\Admin\Role;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminRole;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Suolong\Validator\Validator;

class Status implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();

        Validator::validate($post, [
            'status' => 'must&&int&&intIn:' . implode(',', STATUS),
            'code'   => 'must&&array&&arrayMax:50',
            'code.*' => 'string&&stringMax:40'
        ]);

        $ok = AdminRole::query()
        ->whereIn('code', $post['code'])
        ->update(['status' => $post['status']]);

        if ($ok < 1) 
        {
            throw new ExceptionBase('修改管理员角色状态失败');
        }

        return new Response;
    }
}