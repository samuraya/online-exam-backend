<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;


return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        OAuth2Server::class=>function(ContainerInterface $c) {
            $pdo = $c->get('PdoOauth');
            $storage = new OAuth2\Storage\Pdo($pdo);
            $server = new OAuth2\Server($storage);

            $userCreds = new OAuth2\GrantType\UserCredentials($storage);
            $server->addGrantType($userCreds);
        
            return $server;

        },
        
    ]);


};


