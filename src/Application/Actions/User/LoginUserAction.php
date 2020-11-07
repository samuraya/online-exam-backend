<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Acl\Auth;
use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\User\{UserService, User};

class LoginUserAction extends UserAction
{
	
	protected function action():Response
	{	
		$this->logger->info("was logged in.");
		return $this->respondWithData($this->login());
	}

	protected function login()
	{
		
		return $this->userService->login($this->request);
	}

	protected function getContent()
	{

	}






}