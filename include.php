<?php

require_once __DIR__ . '/vendor/autoload.php';

define('CONF',   require __DIR__ . '/config.php');
define('STATUS', ['Yes' => 1, 'No'  => 2, 'Err' => 3]);

use Illuminate\Database\Capsule\Manager;

$dbManage = new Manager();
$dbManage->addConnection(CONF['db']['admin'], 'admin');
$dbManage->addConnection(CONF['db']['mall'],  'mall');
$dbManage->bootEloquent();