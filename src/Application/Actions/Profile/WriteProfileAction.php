<?php
declare(strict_types=1);

namespace App\Application\Actions\Profile;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

//use App\Infrastructure\Persistence\User\DatabaseUserRepository;

//use App\Domain\Profile\{ProfileService, Profile};

//use \PDO;



final class WriteProfileAction extends ProfileAction
{

	// private $validation;
	// private $userService;

	// public function __construct(UserService $userService)
	// {
	// 	$this->userService = $userService;
	// }

	protected function action():Response
	{
		//$this->logger->info("written to profile");
		return $this->respondWithData(
			$this->profileService
			->writeToProfile($this->request)
		);
		 
	}
	
}