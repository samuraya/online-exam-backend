<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use App\Application\Middleware\ControllerMiddleware;
use Slim\App;

return function (App $app) {
	
    
    $app->add(ControllerMiddleware::class);
    $app->add(SessionMiddleware::class);
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
};
