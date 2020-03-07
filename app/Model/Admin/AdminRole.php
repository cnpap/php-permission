<?php

namespace App\Model\Admin;

use App\Model\Admin\AdminModel;

/**
 * @property string $name
 * @property string $memo
 */
class AdminRole extends AdminModel
{
    protected $table = 'admin_role';

    function permissions() {
        return $this
        ->belongsToMany(
            AdminPermission::class,
            'admin_role_permission',
            'role_code',
            'permission_code'
        );
    }
}