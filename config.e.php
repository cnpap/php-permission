<?php

return [
    'db' => [
        'default' => [
            'database'  => 'database',
            'username'  => 'root',
            'password'  => 'password',
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'port'      => '3306',
            'collation' => 'utf8mb4_unicode_ci',
            'charset'   => 'utf8mb4',
            'prefix'    => ''
        ],
        'mall' => [
            'database'  => 'database',
            'username'  => 'root',
            'password'  => 'password',
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'port'      => '3306',
            'collation' => 'utf8mb4_unicode_ci',
            'charset'   => 'utf8mb4',
            'prefix'    => ''
        ]
    ],
    'app' => [
        'debug'   => true,
        'secret'  => 'custom secret',
        'expired' => 72000
    ],
    'flysystem' => [
        's3' => [
            'username' => '',
            'password' => '',
            'schema'   => '',
            'region'   => '',
            'version'  => 'latest'
        ]
    ],
    'validator' => [
        [
            // 变更格式报错的字段名称为汉语
            // 'code'   => '编号',
            // 'code.*' => '编号集合',
        ],
        [
            'must'          => '%s 是必要的',
            'int'           => '%s 必须是数字',
            'intIn'         => '%s 不符合预期',
            'intMax'        => '%s 不可超过 %d',
            'intBetween'    => '%s 必须介于 %d - %d',
            'string'        => '%s 必须是字符串',
            'stringIn'      => '%s 不符合预期',
            'stringMax'     => '%s 不能超过 %d 个字符',
            'stringLength'  => '%s 应该由 %d 个字符组成',
            'stringBetween' => '%s 字符宽度介于 %d - %d',
            'array'         => '%s 应该是一组数据',
            'arrayMax'      => '%s 的数据不能超过 %d 个',
            'arrayBetween'  => '%s 的数据数量介于 %d - %d',
            'safe'          => '%s 不能包含特殊字符、空白 等',
            'phone'         => '%s 应该是一个 11位 的手机号',
            'eq'            => '%s 应该等价于 %s',
            'notEq'         => '%s 不能等价于 %s',
            'unique'        => '%s 已被使用',
            'exist'         => '%s 不存在'
        ]
    ]
];