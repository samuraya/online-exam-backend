<?php
declare(strict_types=1);

namespace App\Domain\Subject;

use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\Subject\Subject;
use App\Domain\Question\Question;
use App\Domain\DomainException\DomainBadRequestException;

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

		throw new DomainBadRequestException("Not allowed");		
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
			throw new DomainBadRequestException("Invalid subject");			
		}

		$code = 200;
		return array('subject'=>$subject[0],'status_code'=>$code);
	
	}

	private function validateSubject($data)
	{
		$r = v::alnum()
			->length(7,7)
			->validate($data['subject_id']??false);
		if(!$r) throw new DomainBadRequestException("Subject Id must be 7 digits long");

		$r = v::stringType()
			->notEmpty()
			->validate($data['name']??false);
		if(!$r) throw new DomainBadRequestException("Subject name must be given");

		$r = v::alnum()
			->length(8,8)
			->validate($data['instructor_id']??false);
		if(!$r) throw new DomainBadRequestException("Instructor Id must be 8 digits long");
		
	}

}