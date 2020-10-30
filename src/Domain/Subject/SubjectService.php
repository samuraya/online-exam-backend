<?php
declare(strict_types=1);

namespace App\Domain\Subject;

use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\Subject\Subject;
use App\Domain\Question\Question;



use App\Domain\Subject\SubjectNotFoundException;
use App\Infrastructure\Persistence\Subject\SubjectRepository;
use \UnexpectedValueException;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;



final class SubjectService 
{
	
	private $subjectRepository;
	

	public function __construct(SubjectRepository $subjectRepository)
	{
		$this->subjectRepository = $subjectRepository;
	}


	public function listOfSubjects()
	{
		
		$userId = $_SESSION['user_id'] ?? false;

		if($userId){

			$subjects = $this->subjectRepository
				->findSubjectsByInstructor($userId);

			$code = 202;
			return array('subjects'=>$subjects,'status_code'=>$code);
				
		}

		throw new \Exception(__METHOD__.": Not allowed", 401);		
	}



	public function writeToSubject(Request $request)
	{
		
		$data = $request->getParsedBody();

		$this->validateSubject($data);
		
		$id = $data['id'] ?? null;
		$subjectId = $data['subject_id'];
		$subjectName = $data['name'];
		$instructorId = $data['instructor_id'];
		
		
		$subject = new Subject(
			$id,
			$subjectId,
			$subjectName,
			$instructorId,
		);
		 
		$subject = $this
			->subjectRepository
			->save($subject);
		if($subject===false) {
			throw new \Exception("Invalid subject", 1);			
		}

		$code = 200;
		return array('subject'=>$subject[0],'status_code'=>$code);
	
	}


	private function validateSubject($data)
	{
		//var_dump($data); die;
		$r = v::alnum()
			->length(7,7)
			->validate($data['subject_id']??false);
		if(!$r) throw new \Exception(__METHOD__.": Subject Id must be 7 digits long", 1);

		$r = v::stringType()
			->notEmpty()
			->validate($data['name']??false);
		if(!$r) throw new \Exception(__METHOD__.": Subject name must be given", 1);

		$r = v::alnum()
			->length(8,8)
			->validate($data['instructor_id']??false);
		if(!$r) throw new \Exception(__METHOD__.": Instructor Id must be 8 digits long", 1);
		
	}


}

/*
	public function listOfExams()
	{
		$code = 404;
		$exams = false;

		
		$level = $_SESSION['level'] ?? 1;
		$userId = $_SESSION['user_id'] ?? 57212116;

		if($level==0 && $userId){
			//exit('student');
			try{
				$exams = $this->examRepository
				->studentActiveExams($userId);
				
				} catch(ExamNotFoundException $e) {
				return array('error'=>$e->message,'status_code'=>$code);
			}


		}
		if($level==1 && $userId){
			//exit('teacher');
			try{
				$exams = $this->examRepository
				->instructorActiveExams($userId);
				
			} catch(SubjectNotFoundException $e) {
				return array('error'=>$e->message,'status_code'=>$code);
			}
		}

		if($exams) {
			$code = 202;
			return array('exams'=>$exams,'status_code'=>$code);

		} else {
			return array('error'=>'no relevant exam for you ','status_code'=>$code);
		}
		

	
	}

	// public function assembleExam(Request $request):array
	// {

	// }
*/

