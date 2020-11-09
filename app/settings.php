<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
        ],
        'database' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'name' => 'reimburse_api',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
        ]
    ]);
};
