<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Slim\Routing\RouteContext;

use Slim\Psr7\Factory\ResponseFactory;
use App\Application\Helpers\ClientFingerprint;

use App\Domain\DomainException\DomainUnauthorizedException;
use Slim\Exception\HttpUnauthorizedException;

class ControllerMiddleware implements Middleware 
{


	public function process(Request $request, RequestHandler $handler): Response
	{

		// var_dump($_SESSION, $request); die;
		$responseFactory = new ResponseFactory;

		$route = RouteContext::fromRequest($request)
            ->getRoute();

        $routeName = $route->getName();

        $headers = $request->getHeaders();
        $token = $headers['token'][0] ?? FALSE; //change to false when tokens sent over headers

                       
        if($routeName ==="login" || $routeName ==="register") {
            return $handler->handle($request);
        }

        if($routeName ==="logout") {
            
            session_unset();
            session_destroy();
            setcookie('PHPSESSID', "", time() - 3600);
            //throw new DomainUnauthorizedException("user logged out");
            return $handler->handle($request);
        }


         if($routeName === "root") {
            return $handler->handle($request);
        }

        /*
			redirect to login if client tries to access with authorized session from different device and location
		*/
		if(ClientFingerprint::process()===false) {
            
            $response = $responseFactory
            	->createResponse(307,"client fingerprint mismatch");
            return $response->withHeader('Location', '/api/');
        }

        if($token){
            if($this->matchToken($token)){
                //exit('token authorised');
                return $handler->handle($request);
            }
        } else{
            if(isset($_SESSION['token'])){
                //exit('session authorised');
                return $handler->handle($request);
            }
        }

        $response = $responseFactory->createResponse(401,"unauthorised");
        return $response;
        //return $response->withHeader('Location', '/api/');


    }


         public function matchToken($token)
	    {
	    	
	    	$sessionToken = $_SESSION['token'] ?? date('Ymd');
	    	
	    	//return isset($_SESSION['token']); //when only session cookie authentication used
	       return ($token == $sessionToken);  //use when token sent in the header
	    }

}