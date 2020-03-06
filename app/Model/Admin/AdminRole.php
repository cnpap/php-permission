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
}