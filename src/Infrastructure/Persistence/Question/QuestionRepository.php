<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Question;

use \PDO;
use App\Infrastructure\Persistence\Finder;

use App\Domain\Exam\ExamNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;


class QuestionRepository extends BaseRepository
{
	protected $connection;

	public function __construct(PDO $connection)
 	{
 		$this->table = 'question';
 		parent::__construct($connection);
 	}

 	public function findByExam($examId, $isActive=1)
    {
       $sql = Finder::select($this->table)
 			->where('exam_id = ?')
 			->and('is_active = ?')
 			->getSql();
 		
 		Finder::resetAll();

 		$stmt = $this->connection->prepare($sql);
 		$stmt->execute([$examId, $isActive]);

 		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 		if($rows==false || empty($rows)){
 			return array();
 		}		
        
        return $rows;

    }
}