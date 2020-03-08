<?php

use App\Handle\Auth\Login;
use App\Handle\Auth\Check;
use App\Handle\Auth\Password;

use App\Process\CheckAdmin;
use App\Process\WithValidator;

use Suolong\Psr15Router\Router;

use App\Handle\Admin\User\Add    as AdminUserAdd;
use App\Handle\Admin\User\Del    as AdminUserDel;
use App\Handle\Admin\User\Edit   as AdminUserEdit;
use App\Handle\Admin\User\Find   as AdminUserFind;
use App\Handle\Admin\User\Query  as AdminUserQuery;
use App\Handle\Admin\User\Status as AdminUserStatus;

use App\Handle\Admin\Role\Add     as AdminRoleAdd;
use App\Handle\Admin\Role\Del     as AdminRoleDel;
use App\Handle\Admin\Role\Edit    as AdminRoleEdit;
use App\Handle\Admin\Role\Find    as AdminRoleFind;
use App\Handle\Admin\Role\Query   as AdminRoleQuery;
use App\Handle\Admin\Role\Status  as AdminRoleStatus;
use App\Handle\Admin\Role\Outline as AdminRoleOutline;

use App\Handle\Admin\Permission\Add     as AdminPermissionAdd;
use App\Handle\Admin\Permission\Del     as AdminPermissionDel;
use App\Handle\Admin\Permission\Edit    as AdminPermissionEdit;
use App\Handle\Admin\Permission\Find    as AdminPermissionFind;
use App\Handle\Admin\Permission\Query   as AdminPermissionQuery;
use App\Handle\Admin\Permission\Status  as AdminPermissionStatus;
use App\Handle\Admin\Permission\Outline as AdminPermissionOutline;


use App\Process\CheckPermission;

$router = new Router;
$router->post('login', Login::class);
$router->group([
    'processes' => [
        CheckAdmin::class,
        WithValidator::class
    ]
], function (Router $router) {
    $router->get('check', Check::class);
    $router->post('password', Password::class);
    $router->group([
        'proceesses' => [
            CheckPermission::class,
        ]
    ], function (Router $router) {
        $router->group([
            'prefix' => 'admin'
        ], function (Router $router) {
            $router->group([
                'prefix' => 'user'
            ], function (Router $router) {
                $router->post('add',    AdminUserAdd::class);
                $router->delete('del',  AdminUserDel::class);
                $router->put('edit',    AdminUserEdit::class);
                $router->get('find',    AdminUserFind::class);
                $router->get('query',   AdminUserQuery::class);
                $router->post('status', AdminUserStatus::class);
            });
            $router->group([
                'prefix' => 'role'
            ], function (Router $router) {
                $router->post('add',    AdminRoleAdd::class);
                $router->delete('del',  AdminRoleDel::class);
                $router->put('edit',    AdminRoleEdit::class);
                $router->get('find',    AdminRoleFind::class);
                $router->get('query',   AdminRoleQuery::class);
                $router->post('status', AdminRoleStatus::class);
                $router->get('outline', AdminRoleOutline::class);
            });
            $router->group([
                'prefix' => 'permission'
            ], function (Router $router) {
                $router->post('add',    AdminPermissionAdd::class);
                $router->delete('del',  AdminPermissionDel::class);
                $router->put('edit',    AdminPermissionEdit::class);
                $router->get('find',    AdminPermissionFind::class);
                $router->get('query',   AdminPermissionQuery::class);
                $router->post('status', AdminPermissionStatus::class);
                $router->get('outline', AdminPermissionOutline::class);
            });
        });
    });
});