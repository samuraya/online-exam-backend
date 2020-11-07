<?php
declare(strict_types=1);

namespace App\Application\Actions\Profile;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

final class ViewProfileAction extends ProfileAction
{

	protected function action():Response
	{
		return $this->respondWithData($this->profileService->retrieveProfile($this->request));
		 
	}
}