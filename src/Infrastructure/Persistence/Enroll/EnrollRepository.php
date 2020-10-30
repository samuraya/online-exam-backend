<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Enroll;

use \PDO;
use App\Infrastructure\Persistence\Finder;
use App\Infrastructure\Persistence\BaseRepository;

class EnrollRepository extends BaseRepository
{


	protected $connection;

	public function __construct(PDO $connection)
 	{
 		$this->table = "enrolled";
 		$this->connection = $connection;
 	}

}
