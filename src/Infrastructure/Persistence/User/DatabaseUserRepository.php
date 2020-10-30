<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \PDO;

use Slim\Exception\HttpBadRequestException as BadRequest;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\BaseRepository;

class DatabaseUserRepository extends BaseRepository implements UserRepository
{

	protected $connection;
	
 	public function __construct(PDO $connection)
 	{
 		$this->table = 'user';
 		parent::__construct($connection);
 	}


 	public function insertUser(User $user):int
 	{
 		$id = -1;
 		$password = password_hash($user->getPassword(), PASSWORD_DEFAULT);
 		$sql = "insert into ".self::TABLE." (id, password, level) values(?,?,?);";
 		$stmt = $this->connection->prepare($sql);
 		$result = $stmt->execute([$user->getId(),$password, $user->getLevel()]);
 		 		 		 		
 		if($result){
 			return $user->getId();
 		}

 		return $id;

 	}

 	public function findAll():array 
 	{

 	}

 	public function findUserOfId($id)
 	{
 		
 		$sql = 'SELECT * FROM ' . $this->table.' WHERE user_id = ?';
//exit($id);
		$stmt = $this->connection->prepare($sql);			
		$stmt->execute([$id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	//exit($row['user_id']);
		
		if ($row===false) {
            return false;
            //throw new UserNotFoundException();
        }

        return new User(
        		(int)$row['id'],
        		(int)$row['user_id'],
        		(string)$row['password'],
        		(int)$row['level']);
       //var_dump($user); die;
        //return $user;




 	}


}