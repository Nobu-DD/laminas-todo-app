<?php

declare(strict_types=1);

return [
    'driver'   => 'pdo_mysql',
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'port'     => getenv('DB_PORT') ?: '3306',
    'dbname'   => getenv('DB_NAME') ?: 'todo_db',
    'user'     => getenv('DB_USER') ?: 'todo_user',
    'password' => getenv('DB_PASSWORD') ?: 'todo_pass',
    'charset'  => 'utf8mb4',
];