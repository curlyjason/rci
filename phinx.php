<?php
include CONFIG . DS . 'conv.php';

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
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => '127.0.0.1',
            'name' => SHORT_NAME,
            'user' => DB_USERNAME,
            'pass' => DB_USER_PASS,
            'port' => DB_PORT,
            'charset' => 'utf8',
        ],
        'test' => [
            'adapter' => 'mysql',
            'host' => '127.0.0.1',
            'name' => 'test',
            'user' => 'root',
            'pass' => DB_ROOT_PASS,
            'port' => DB_PORT,
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
