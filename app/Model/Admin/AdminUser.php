<?php

namespace App\Model\Admin;

use App\Model\Admin\AdminModel;
use App\Model\Admin\Relation\AdminUserRole;

/**
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $memo
 */
class AdminUser extends AdminModel
{
    protected $table = 'admin_user';

    function roles() {
        return $this
        ->belongsToMany(AdminRole::class)
        ->using(AdminUserRole::class);
    }
}