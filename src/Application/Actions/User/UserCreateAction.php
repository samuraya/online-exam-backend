<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

use App\Domain\User\{UserService, User};

final class UserCreateAction extends UserAction
{

	protected function action():Response
	{
		
		return $this->respondWithData($this->create());
		 
	}

	protected function create()
	{
		return $this->userService->createUser($this->request);
	}
	
}