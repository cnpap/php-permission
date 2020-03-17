<?php

namespace App\Handle\Admin\Permission;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminPermission;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Suolong\Validator\Validator;

class Del implements RequestHandlerInterface
{
    function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();

        Validator::validate($post, [
            'code'   => 'must&&array&&arrayMax:50',
            'code.*' => 'must&&string&&safe&&stringMax:40'
        ]);

        $ok = AdminPermission::query()
        ->whereIn('code', $post['code'])
        ->delete();

        if ($ok < 1) 
        {
            throw new ExceptionBase('删除管理员权限失败');
        }

        return new Response;
    }
}