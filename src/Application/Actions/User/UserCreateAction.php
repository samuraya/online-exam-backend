<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

//use App\Infrastructure\Persistence\User\DatabaseUserRepository;

use App\Domain\User\{UserService, User};

//use \PDO;



final class UserCreateAction extends UserAction
{

	// private $validation;
	// private $userService;

	// public function __construct(UserService $userService)
	// {
	// 	$this->userService = $userService;
	// }

	protected function action():Response
	{
		$this->logger->info("created user");
		return $this->respondWithData($this->create());
		 
	}

	protected function create()
	{

		return $this->userService->createUser($this->request);

	}
	
}