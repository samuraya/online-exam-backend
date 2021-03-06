<?php
declare(strict_types=1);

namespace App\Domain\User;

use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use \UnexpectedValueException;
use Slim\Exception\HttpBadRequestException;
use App\Domain\DomainException\DomainUnauthorizedException;
use App\Domain\DomainException\DomainBadRequestException;




final class UserService 
{

	private const DEFAULT_LEVEL = 0;
	
	private $repository;

	public function __construct(UserRepository $repository)
	{
		$this->repository = $repository;
	}

	public function createUser(Request $request):array
	{

		if(empty($request->getAttribute('validation'))) {
			 
			$data = $request->getParsedBody();

			$id = $data['id'] ?? null;
			$userId = $data['user_id'];
			$level = $data['level'] ?? self::DEFAULT_LEVEL;
			$password = password_hash($data['password'], PASSWORD_DEFAULT);

			$user = new User(
					$id,
					$userId,
					$password,
					$level
					
				);
						
			$userNew = $this->repository->insert($user);
			if($userNew===false) {
				throw new DomainBadRequestException('Registration failed');			
			}

			$userNew = $userNew[0];
			$this->setSession($user);
			unset($userNew['password']);
			unset($userNew['id']);
			$userNew['token']=$_SESSION['token'];
			$code = 200;
			return array('user'=>$userNew,'status_code'=>$code);

		}
		throw new DomainBadRequestException($request->getAttribute('validation'));
		
	}

	public function login(Request $request):array
	{
	
		$data = $request->getParsedBody();
	
		$username = $data['user_id'] ?? FALSE;

		if($username){
			$user = $this->repository->findUserOfId($username);
					
			if($user){
				if(password_verify($data['password'], $user->getPassword())) {
					session_regenerate_id(true);
					$this->setSession($user);
					
					$body = [
						
						'user_id'=>$user->getUserId(),
						'level'=>$user->getLevel(),
						'token'=>$_SESSION['token']
					];
					$code = 202;

					return array('user'=>$body,'status_code'=>$code);
				}
							
			}
			throw new DomainUnauthorizedException("Wrong login details");	
			
		} 
		throw new DomainBadRequestException('No user id given');

	}

	private function setSession($user):void
	{		
		$token = bin2hex(random_bytes(16));
		$_SESSION['token'] = $token;
		$_SESSION['user_id'] = $user->getUserId();
		$_SESSION['level'] = $user->getLevel();
		
	}

	public function retrieveUserBySession() 
	{
		 
		$userId = $_SESSION['user_id'] ?? FALSE;
		$token = $_SESSION['token'] ?? FALSE;

		if($userId){
			$user = $this->repository->findUserOfId($userId);
			
			if($user){
					
					$body = [
						'user_id'=>$user->getUserId(),
						'level'=>$user->getLevel(),
						'token'=>$token
					];
					$code = 202;

				return array('user'=>$body,'status_code'=>$code);

			}
		}
		throw new DomainUnauthorizedException("unauthorized. please login",401);		

	}

}
