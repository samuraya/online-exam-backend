<?php
declare(strict_types=1);

namespace App\Domain\Exam;

use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\Exam\Exam;
use App\Domain\Question\Question;



use App\Domain\Exam\ExamNotFoundException;
use App\Infrastructure\Persistence\Exam\ExamRepository;
use \UnexpectedValueException;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;



final class ExamService 
{
	
	private $examRepository;
	

	public function __construct(ExamRepository $examRepository)
	{
		$this->examRepository = $examRepository;
	}

	public function closeExam(Request $request)
	{
		$examId = $request->getAttribute('examId')??false;
	
		$r = v::intVal()->validate($examId??false);
        if(!$r) throw new \Exception(__METHOD__.": exam id must be integer ", 1);

		$exam = $this->examRepository->updateStatus($examId);
		return array('exam'=>$exam[0],'status_code'=>200);
		
	}


	public function writeToExam(Request $request)
	{
		$data = $request->getParsedBody();

		$this->validateExam($data);

		$id = $data['exam_id'] ?? null;
		$examName = $data['name'];
		$subjectId = $data['subject_id'];
		$datetime = $data['datetime'];
		$status = $data['status'] ?? 1;
		
		$exam = new Exam(
			$id,
			$examName,
			$subjectId,
			$datetime,
			$status
		);
// var_dump($exam); die();

		$exam = $this
			->examRepository
			->save($exam);
		$code = 200;
		return array('exam'=>$exam[0],'status_code'=>$code);
	}


	public function listOfExams($isActive = 1): array
	{
		//status=1 is active exam, if status=0 then exam finished
		
		$level = $_SESSION['level'];
		$userId = $_SESSION['user_id'];
		
		if($level==0){
			$exams = $this->examRepository
				->studentExams($userId,$isActive);
		}
		if($level==1){
			$exams = $this->examRepository
				->instructorExams($userId,$isActive);
		} 
		$code = 202;
		return array('exams'=>$exams,'status_code'=>$code);
	}

	private function validateExam($data)
	{		
		$r = v::stringType()
			->notEmpty()
			->validate($data['name']??false);
		if(!$r) throw new \Exception(__METHOD__.": Exam name must be given", 1);
		
		$r = v::alnum()
			->length(8,8)
			->validate($data['subject_id']??false);
		
		//if(!$r) throw new \Exception(__METHOD__.": Subject Id must be 8 digits long", 1);
		
		$r = v::dateTime('Y-m-d H:i:s')
			->validate($data['datetime']??false);
		//if(!$r) throw new \Exception(__METHOD__.": date time of Y-m-d format should be given", 1);
		
		$r = v::intVal()
			->between(0, 1)
			->validate($data['status']??1);
		if(!$r) throw new \Exception(__METHOD__.": integer value of 0 or 1 must be given ", 1);
	}
}