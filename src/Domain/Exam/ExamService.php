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
use App\Domain\DomainException\DomainBadRequestException;




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
        if(!$r) throw new DomainBadRequestException("exam id must be integer");
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

		$exam = $this
			->examRepository
			->save($exam);
		if($exam===false){
			throw new DomainBadRequestException("couldnot save exam");
		}
		$code = 200;
		return array('exam'=>$exam[0],'status_code'=>$code);
	}

	public function listOfExams($isActive = 1): array
	{
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
		if(!$r) throw new DomainBadRequestException("Exam name must be given");
		
		$r = v::alnum()
			->length(7,7)
			->validate($data['subject_id']??false);
		
		if(!$r) throw new DomainBadRequestException("Subject Id must be 7 digits long");
		
		$r = v::dateTime('Y-m-d')
			->validate($data['datetime']??false);
		if(!$r) throw new DomainBadRequestException("date time of Y-m-d format should be given");
		
		$r = v::intVal()
			->between(0, 1)
			->validate($data['status']??1);
		if(!$r) throw new DomainBadRequestException("integer value of 0 or 1 must be given ");
	}
}