<?php

declare(strict_types=1);

namespace Todo;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'todo' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/api/todos[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\TodoController::class,
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\TodoController::class => Controller\TodoControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            Model\TodoTable::class => static function ($container) {
                $adapter      = $container->get(AdapterInterface::class);
                $tableGateway = new TableGateway('todos', $adapter);
                return new Model\TodoTable($tableGateway);
            },
        ],
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];