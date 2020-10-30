<?php
declare(strict_types=1);

namespace App\Application\Acl;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


interface AuthenticateInterface
{
	public function login(Request $request);
}