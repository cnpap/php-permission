<?php

use App\Handle\Auth\Login;
use App\Handle\Auth\Password;
use App\Process\CheckAdmin;
use App\Process\WithValidator;
use Suolong\Psr15Router\Router;

use App\Handle\Admin\User\Add as AddAdminUser;

$router = new Router;
$router->post('login', Login::class);
$router->group([
    'processes' => [
        CheckAdmin::class,
        WithValidator::class
    ]
], function (Router $router) {
    $router->group([
        'prefix' => 'admin'
    ], function (Router $router) {
        $router->post('password', Password::class);
        $router->group([
            'prefix' => 'user'
        ], function (Router $router) {
            $router->post('add', AddAdminUser::class);
        });
    });
});