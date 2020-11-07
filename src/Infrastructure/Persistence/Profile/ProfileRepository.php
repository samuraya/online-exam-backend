<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Profile;

use \PDO;
use App\Infrastructure\Persistence\Finder;

use App\Domain\Profile\ProfileNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;

class ProfileRepository extends BaseRepository
{
	protected $connection;

	public function __construct(PDO $connection)
 	{
 		$this->table = 'profile';
 		parent::__construct($connection);
 	}

 	public function findProfileById($userId)
 	{
 		$sql = Finder::select($this->table)
 			->where('id = ?')
 			->getSql();
 		$stmt = $this->connection->prepare($sql);
 		$stmt->execute([$userId]);
 		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 		if($rows==false || empty($rows)){
 			return array();
 			
 		}
        return $rows;
 	}
}