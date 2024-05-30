<?php
include 'config/conv.php';

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds',
    ],
    'templates' => [
        'file' => 'src/Utilities/Phinx/Migration.template.php.dist',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'dsn' => 'mysql:host=mysql;dbname=rci;port=3035',
//            'host' => getenv('DB_HOST'),
//            'name' => getenv('SHORT_NAME'),
            'user' => getenv('DB_USERNAME'),
            'pass' => getenv('DB_USER_PASS'),
//            'port' => getenv('DB_PORT'),
            'charset' => 'utf8',
        ],
        'test' => [
            'adapter' => 'mysql',
            'host' => getenv('DB_HOST'),
            'name' => 'test',
            'user' => 'root',
            'pass' => getenv('DB_ROOT_PASS'),
            'port' => getenv('DB_PORT'),
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
