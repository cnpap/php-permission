<?php

namespace App\Process;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminModel;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckPermission implements MiddlewareInterface
{
    function process(ServerRequestInterface $request, RequestHandlerInterface $handle): ResponseInterface
    {
        $user = $request->getAttribute('user');

        if (is_null($user)) {
            return new Response(401, [], '请登陆');
        }

        if (isset($user['code']) && $user['code'] === '001_admin') {
            return $handle->handle($request);
        }

        $sql = "
        select code from admin_permission
        where
        path = :path and
        code in
        (
            select permission_code as code from admin_user_permission
            where
            user_code = :user_code
            union
            select permission_code as code from admin_role_permission
            where
            role_code in
            (
                select role_code from admin_user_role
                where
                user_code = :user_code
            )
        );
        ";

        $path = $request->getMethod() . $request->getUri()->getPath();

        $check = (new AdminModel)->getConnection()->getPdo()->prepare($sql);
        $check->bindParam('path', $path);
        $check->bindParam('user_code', $user['code']);
        $ok = $check->execute();

        if ($ok !== true) {
            throw new ExceptionBase('权限判定失败');
        }

        if ($check->rowCount() !== 1) {
            return new Response(403, [], '缺少访问权限');
        }

        return $handle->handle($request);
    }
}