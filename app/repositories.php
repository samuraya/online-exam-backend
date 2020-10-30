<?php
declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Profile\ImageRepository;
use App\Infrastructure\Persistence\User\DatabaseUserRepository;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;


return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(DatabaseUserRepository::class),
       // AuthenticateInterface::class => \DI\autowire(DbAuth::class),
        PDO::class=>function(ContainerInterface $c) {
        	$dbSettings=$c->get('settings')['db'];
        	$dsn = 'mysql:host=' . $dbSettings['host'] . ';dbname=' . $dbSettings['dbname'];
        	$options = $dbSettings['flags'];
                
           	return new PDO($dsn, $dbSettings['user'], $dbSettings['password'], $options);
        },
        
        ImageRepository::class=>function(ContainerInterface $c) {
            $root_path=$c->get('settings')['root_path'];
            return new ImageRepository($root_path);
        }
        
    ]);
};
