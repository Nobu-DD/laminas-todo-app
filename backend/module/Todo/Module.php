<?php

declare(strict_types=1);

namespace Todo;

use Laminas\Http\Request;
use Laminas\Mvc\MvcEvent;

class Module
{
    public function getConfig(): array
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e): void
    {
        $eventManager = $e->getApplication()->getEventManager();

        // すべてのレスポンスに CORS ヘッダーを付与
        $eventManager->attach(MvcEvent::EVENT_FINISH, function (MvcEvent $e): void {
            $response = $e->getResponse();
            $headers  = $response->getHeaders();
            $headers->addHeaderLine('Access-Control-Allow-Origin', '*');
            $headers->addHeaderLine('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $headers->addHeaderLine('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        });

        // OPTIONS プリフライトリクエストを即時返却
        $request = $e->getRequest();
        if ($request instanceof Request && $request->getMethod() === 'OPTIONS') {
            $response = $e->getResponse();
            $response->setStatusCode(200);
            $response->getHeaders()
                ->addHeaderLine('Access-Control-Allow-Origin', '*')
                ->addHeaderLine('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->addHeaderLine('Access-Control-Allow-Headers', 'Content-Type, Authorization');
            $response->send();
            exit;
        }
    }
}