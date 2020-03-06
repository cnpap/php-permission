<?php

namespace App\Model\Admin;

use App\Model\Admin\AdminModel;

/**
 * 
 * @property string $parent_code
 * @property int    $menu
 * @property string $path
 * @property string $name
 * @property string $memo
 * @property string $icon
 */
class AdminPermission extends AdminModel
{
    protected $table = 'admin_permission';
}