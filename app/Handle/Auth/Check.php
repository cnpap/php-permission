<?php

namespace App\Handle\Auth;

use App\Exception\Base\ExceptionBase;
use App\Model\Admin\AdminModel;
use App\Model\Admin\AdminPermission;
use GuzzleHttp\Psr7\Response;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Check implements RequestHandlerInterface
{

    function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $menu = STATUS['Yes'];
        $data = [];
        $data['user'] = $user;

        if ($user['code'] === '001_admin') {
            $data['menu'] = AdminPermission::query()
            ->where('menu', STATUS['Yes'])
            ->get(
                [
                    'code',
                    'parent_code',
                    'menu',
                    'name',
                    'icon',
                    'path'
                ]
            );
        } else {
            $menuSql = "
            select
            code,
            parent_code,
            menu,
            name,
            icon,
            path
            from admin_permission
            where
            menu = :menu and
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

            $queryMenu = (new AdminModel)->getConnection()->getPdo()->prepare($menuSql);
            $queryMenu->bindParam('user_code', $user['code']);
            $queryMenu->bindParam('menu', $menu);
            $ok = $queryMenu->execute();

            if ($ok !== true) {
                throw new ExceptionBase('获取菜单列表失败');
            }

            $data['menu'] = $queryMenu->fetchAll(PDO::FETCH_ASSOC);
        }

        $data = json_encode($data);

        return new Response(200, ['Content-Type' => ['application/json']], $data);
    }
}