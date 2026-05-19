<?php

declare(strict_types=1);

return [
    'modules' => [
        'Laminas\Router',
        'Laminas\Db',
        'Todo',
    ],
    'module_listener_options' => [
        'module_paths' => [
            './module',
            './vendor',
        ],
        'config_glob_paths' => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ],
];