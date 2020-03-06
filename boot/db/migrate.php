<?php

use App\Model\Admin\AdminModel;

include __DIR__ . '/../../include.php';

$pdo = (new AdminModel)->getConnection()->getPdo();
$pdo->exec(file_get_contents(__DIR__ . '/admin/admin_user.sql'));
$pdo->exec(file_get_contents(__DIR__ . '/admin/admin_role.sql'));
$pdo->exec(file_get_contents(__DIR__ . '/admin/admin_permission.sql'));
$pdo->exec(file_get_contents(__DIR__ . '/admin/sync.sql'));