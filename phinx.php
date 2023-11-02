<?php
include ROOT . '/conv.php';

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => '127.0.0.1',
            'name' => 'my_app',
            'user' => 'my_app',
            'pass' => 'secret',
            'port' => DB_PORT,
            'charset' => 'utf8',
        ],
        'test' => [
            'adapter' => 'mysql',
            'host' => '127.0.0.1',
            'name' => 'test_my_app',
            'user' => 'root',
            'pass' => 'root',
            'port' => DB_PORT,
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
