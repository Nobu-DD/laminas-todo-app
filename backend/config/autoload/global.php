<?php

declare(strict_types=1);

return [
    'db' => [
        'driver'   => 'Pdo_Mysql',
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'port'     => (int) (getenv('DB_PORT') ?: 3306),
        'database' => getenv('DB_NAME') ?: 'todo_db',
        'username' => getenv('DB_USER') ?: 'todo_user',
        'password' => getenv('DB_PASSWORD') ?: 'todo_pass',
        'charset'  => 'utf8mb4',
    ],
    'service_manager' => [
        'factories' => [
            \Laminas\Db\Adapter\AdapterInterface::class =>
                \Laminas\Db\Adapter\AdapterServiceFactory::class,
        ],
    ],
];