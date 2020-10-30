<?php
declare(strict_types=1);

namespace App\Application\Handlers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Slim\Exception\HttpBadRequestException;



class AuthenticationHandler implements RequestHandlerInterface
{


	public function __invoke($req,$res,$next)
	{
		echo "this is AuthenticationHandler";
		var_dump($req);
		die;
	}


}