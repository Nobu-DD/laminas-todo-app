<?php

declare(strict_types=1);

namespace Todo\Controller;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Todo\Model\TodoTable;

class TodoControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        ?array $options = null
    ): TodoController {
        return new TodoController($container->get(TodoTable::class));
    }
}