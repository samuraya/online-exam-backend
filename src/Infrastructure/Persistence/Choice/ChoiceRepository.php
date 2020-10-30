<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Choice;

use \PDO;
use App\Infrastructure\Persistence\Finder;

use App\Domain\Question\QuestionNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;


class ChoiceRepository extends BaseRepository
{
  protected $connection;

  public function __construct(PDO $connection)
  {
    $this->table = 'choice';
    parent::__construct($connection);
  }
  public function findByQuestion($questionId)
    {
       $sql = Finder::select($this->table)
      ->where('question_id = ?')
      ->getSql();

    Finder::resetAll();

    $stmt = $this->connection->prepare($sql);
    $stmt->execute([$questionId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($rows==false || empty($rows)){
      return array();
    }   
        
        return $rows;
    }

  


/*

  public function studentActiveExams($studentId, $isActive = 1)
  { 
    $this->joinSubjectExamEnrolled();
    $rows = $this->execute([$studentId, $isActive]);
    return $rows;

  }

  public function instructorActiveExams($instructorId, $isActive = 1)
  {
    $this->joinSubjectExam();
    $rows = $this->execute([$instructorId, $isActive]);
    return $rows; 
  }


  public function joinSubjectExam()
  {
    $sql = " SELECT exam.id as exam_id,"
    ." exam.name as exam_name,subject.id as subject_id,subject.name as subject"
    ." FROM subject JOIN exam"
    ." ON exam.subject_id = subject.id"
    ." WHERE subject.instructor_id = ?"
    ." AND exam.is_active = ?";
    
    $this->sql = $sql;
    
  }

  public function joinSubjectExamEnrolled()
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