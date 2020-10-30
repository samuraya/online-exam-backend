<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Subject;

use App\Domain\Subject\SubjectRepositoryInterface;

use \PDO;
use App\Infrastructure\Persistence\Finder;

use App\Domain\Subject\SubjectNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;

class SubjectRepository extends BaseRepository implements SubjectRepositoryInterface
{
	protected $connection;

	public function __construct(PDO $connection)
 	{
 		$this->table = 'subject';
 		parent::__construct($connection);
 	}

 
 	public function findSubjectsByInstructor($instructorId)
 	{
 		$sql = Finder::select($this->table)
 			->where('instructor_id = ?')
 			->getSql();
 		$stmt = $this->connection->prepare($sql);
 		$stmt->execute([$instructorId]);
 		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 		if($rows==false || empty($rows)){
 			return array();
 			
 		}
        return $rows;
 	}
/*
	public function joinSubjectExam():void
	{
		$sql = " SELECT exam.id as exam_id,"
		." exam.name as exam_name,subject.id as subject_id,subject.name as subject"
		." FROM subject JOIN exam"
		." ON exam.subject_id = subject.id"
		." WHERE subject.instructor_id = ?"
		." AND exam.is_active = ?";
		
		$this->sql = $sql;
		
	}

	public function joinSubjectExamEnrolled():void
	{
		$sql = "SELECT exam.id as exam_id,"
		." exam.name as exam_name,subject.id as subject_id,subject.name as subject"
		." FROM subject JOIN exam"
		." ON exam.subject_id = subject.id"
		." JOIN enrolled"
		." ON enrolled.subject_id = subject.id"
		." WHERE enrolled.student_id = ?"
		." AND exam.is_active = ?";

		$this->sql = $sql;
		
		
	}
*/
						


}