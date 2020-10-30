<?php
/*
declare(strict_types=1);

namespace App\Application\Acl;

use App\Domain\User\UserRepository;
use App\Domain\User\UserNotFoundException;


use Psr\Http\Message\ServerRequestInterface as Request;

class Auth implements AuthenticateInterface
{

	const ERROR_AUTH = 'ERROR: authentication error';
	protected $repository;
	protected $token = 'wrong';

	public function __construct(UserRepository $repository)
	{
		$this->repository = $repository;
	}

	public function login(Request $request)
	{
		
		$body = self::ERROR_AUTH;
		$code = 401;
		$info = FALSE;


		$data = $request->getParsedBody();
		$username = $data['id'] ?? FALSE;

		if($username){
			try{
				$user = $this->repository->findUserOfId($username);
			} catch(UserNotFoundException $e) {
				return array('error'=>$e->message,'status_code'=>$code);
			}
			
			
			if($user){
				if(password_verify($data['password'], $user->getPassword())) {
					session_regenerate_id(true);
					$this->setSession($user->getId());
					$user->unset('password');
					$body = [
						'id'=>$user->getId(),
						'level'=>$user->getLevel(),
						'token'=>$this->token
					];
					$code = 202;

				}
			}
		}
		
		return array('user'=>$body,'status_code'=>$code);

	}

	private function setSession($userId)
	{
		
		$this->token = bin2hex(random_bytes(16));
		$_SESSION['token'] = $this->token;
		$_SESSION['user_id'] = $userId;
		
	}
*/
}