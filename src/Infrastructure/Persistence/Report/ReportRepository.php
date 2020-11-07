<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Report;

use \PDO;
use App\Infrastructure\Persistence\Finder;

class ReportRepository
{
	protected $connection;

	public function __construct(PDO $connection)
 	{
 		$this->connection = $connection;
 	}

 	public function instructorReportOneStudent($studentId, $examId, $isActive = 0)
 	{
 		$sql = $this->joinCombineAnsweWithCorrectChoice();
		
		$stmt = $this->connection->prepare($sql);
 		$stmt->execute([$examId, $isActive, $studentId,$examId]);
 		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
 	
 		if($rows==false || empty($rows)){
 			return array();
 			
 		}
 		
        return $rows;
 	}


 	public function instructorReportByExamAllStudents($examId, $isActive = 0)
	{
		$sql = $this->joinChoiceAnswerQuestionExam();
		
		$stmt = $this->connection->prepare($sql);
 		$stmt->execute([$examId, $isActive]);
 		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 		if($rows==false || empty($rows)){
 			return array();
 			
 		}
 		return $rows;
	}

	public function joinCombineAnsweWithCorrectChoice()
	{
		return "select A.Question, A.Choice, B.Correct"
		." from (select question.content as Question,"
		." choice.content as Choice from answer"
		." JOIN choice ON choice.id = answer.choice_id"
		." JOIN question ON question.id = answer.question_id"
		." JOIN exam ON exam.id = question.exam_id"
		." where exam.id = ? and exam.is_active = ?"
		." and answer.user_id = ?) AS A"
		." JOIN (select question.content as Question,"
		." choice.content as Correct from choice"
		." JOIN question ON question.id = choice.question_id"
		." JOIN exam ON exam.id = question.exam_id"
		." where exam.id = ? and choice.is_correct = '1') AS B"
		." ON A.Question = B.Question";
	}


	public function joinChoiceAnswerQuestionExam()
	{
		return " SELECT answer.user_id as Student,"
		
		." SUM(CASE WHEN choice.is_correct = '1' THEN 1 ELSE 0 END) AS Correct,"
       	." SUM(CASE WHEN choice.is_correct = '0' THEN 1 ELSE 0 END) AS Wrong"
		
		." FROM answer JOIN choice"		
		." ON choice.id = answer.choice_id"
		." JOIN question"
		." ON question.id = answer.question_id"
		." JOIN exam"
		." ON exam.id = question.exam_id"
		." WHERE (exam.id = ?"
		
		." AND exam.is_active = ?)"
		." group by answer.user_id";

	}

}
