<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;


class LevelGuardMiddleware implements Middleware
{
     

    //  public function __construct()
    // {
       
    // }


    public function process(Request $request, RequestHandler $handler): Response
    {   
        
       $level = $_SESSION['level'] ?? false;

       if($level<1) {
            throw new \Exception(__METHOD__." : Insufficient permission", 1);           

       }

        return $handler->handle($request);
       
       
    }


   
}
