<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Exam;

use \PDO;
use App\Infrastructure\Persistence\Finder;

use App\Domain\Exam\ExamNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;
use App\Domain\DomainException\DomainBadRequestException;

class ExamRepository extends BaseRepository
{
	protected $connection;

	public function __construct(PDO $connection)
 	{
 		$this->table = 'exam';
 		parent::__construct($connection);
 	}



 	public function updateStatus($examId)
 	{
 		$sql = "UPDATE " .$this->table
            ." SET is_active = '0'"
            ." WHERE id = ?";   

        $result = $this->flush($sql, array($examId));
        if($result===true){           
            return $this->findById($examId);
        }
        throw new DomainBadRequestException("Error while updating the record");  
 	}

	public function studentExams($studentId, $isActive = 1)
	{	
		$sql = $this->joinSubjectExamEnrolled();
		
 		$stmt = $this->connection->prepare($sql);
 		$stmt->execute([$studentId, $isActive]);
 		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 		if($rows==false || empty($rows)){
 			return array();
 			
 		}
        return $rows;

	}

	public function instructorExams($instructorId, $isActive = 1)
	{
		$sql = $this->joinSubjectExam();
		
		$stmt = $this->connection->prepare($sql);
 		$stmt->execute([$instructorId, $isActive]);
 		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 		if($rows==false || empty($rows)){
 			return array();
 			
 		}
        return $rows;	
	}	

	public function joinSubjectExam()
	{
		return " SELECT exam.id as exam_id,"
		." exam.name as name,subject.subject_id as subject_id,subject.name as subject"
		." FROM subject JOIN exam"
		." ON exam.subject_id = subject.subject_id"
		." WHERE subject.instructor_id = ?"
		." AND exam.is_active = ?";
				
	}

	public function joinSubjectExamEnrolled()
	{
		return "SELECT exam.id as exam_id,"
		." exam.name as name,subject.subject_id as subject_id,subject.name as subject"
		." FROM subject JOIN exam"
		." ON exam.subject_id = subject.subject_id"
		." JOIN enrolled"
		." ON enrolled.subject_id = subject.subject_id"
		." WHERE enrolled.student_id = ?"
		." AND exam.is_active = ?";
	
	}
}