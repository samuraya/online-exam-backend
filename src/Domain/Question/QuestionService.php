<?php
declare(strict_types=1);

namespace App\Domain\Question;

use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\Question\Question;
use App\Domain\Choice\ChoiceService;

use App\Domain\Exam\ExamNotFoundException;
use App\Domain\Question\QuestionNotFoundException;
use App\Infrastructure\Persistence\Question\QuestionRepository;
use App\Domain\DomainException\DomainBadRequestException;

use \UnexpectedValueException;
use App\Domain\Exam\ExamService;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

use Slim\Psr7\Factory\RequestFactory;

final class QuestionService 
{
	
	private $questionRepository;

	public function __construct(
		QuestionRepository $questionRepository,
		ExamService $examService, 
		ChoiceService $choiceService)
	{
		$this->questionRepository = $questionRepository;
		$this->examService = $examService;
		$this->choiceService = $choiceService;
		
	}


	public function writeToQuestion(Request $request)
	{
		
		if($_SESSION['level']!==1) {
			throw new DomainBadRequestException("user not allowed to write to exam");
		}
					
		$data = $request->getParsedBody();	
		

		$questions = [];
		$choicesWritten = [];

		foreach ($data as $question) {			
			$id = $question['id'] ?? null;
			$examId = $question['exam_id'];
			$content = $question['content'];
			$status = $question['is_active'] ?? 1;
			$choices = $question['choices']??false;

			
			
			$question = new Question(
				$id,
				$examId,
				$content,
				$status
			);
		
		
			$question = $this->questionRepository->save($question);


			$question = $question[0];
		
			$questions[] = $question;
			
			if($choices!== false){

				$choices = $this->choiceService->persist($choices,$question['id']);	

				$choicesWritten[]=$choices;

			}

		}			
			$code = 200;
			return array(
				'questions'=>$questions,
				'choices'=>$choicesWritten,
				'status_code'=>$code
			);
	}


	public function fetchAllQuestionsByExam($args)
	{
		
		$examServiceResponse = $this->examService->listOfExams();

	/* fetches all the exams that user has access to
	*/
		$examServiceResponse = $examServiceResponse['exams'];
		$examId = $args['examId']; //requested ID

	/* checkes if requested exam Id is inside of allowed exams 	  for that user. If not return Unaothorized error
	*/
		$examFound = false;

		foreach($examServiceResponse as $exam){
			if($exam['exam_id']==$examId) $examFound = true;
		}
		if($examFound===false){
			throw new DomainBadRequestException("Not allowed to see this examId");
		}
					
	/*	get the questions by exam id
		if user is legal, then fetch all questions for the requested examId
	*/
		
		$questions = $this
			->questionRepository
			->findByExam($examId);
		
		if(empty($questions)) {
			$code = 200;
			return array(
				'questions_choices'=>array($examId=>array()),
				'status_code'=>$code
			);
		}
	/* Once exam questions found
		time to fetch choices for each question id
	*/
		$questionChoices=[];
		 
			foreach($questions as $question) {
				$questionChoices[$question['id']] = 
				array($question['content']=>
						$this->choiceService
						->fetchAllChoicesByQuestion(
							array('questionId'=>$question['id'])
						)
				);
				

			} 
		
	/*
		If reached till here means user requested Exam Id is legal, all questions under that exam Id found, and all respective choices belonging to each question Id are attached. Respond with 200
	*/
		$code = 200;
		return array(
			'questions_choices'=>$questionChoices,
			'status_code'=>$code
		);
	
	}


	public function deleteQuestion(Request $request)
    {
        $questionId = $request->getAttribute('questionId') ?? false;

        $r = v::intVal()->validate($questionId??false);
        if(!$r) throw new DomainBadRequestException("choice id must be integer ");
        $result = $this->questionRepository->delete($questionId);
        if($result===false) {
        	return array('result'=>'not deleted','status_code'=>200); 
        }

        return array('result'=>'deleted','status_code'=>200);  
    }

	public function validateQuestion($data)
	{
		
		$r = v::intVal()
			->validate($data['id']??null);
		if(!$r) throw new DomainBadRequestException("question id must be integer ");

		$r = v::intVal()
			->validate($data['exam_id']);
		if(!$r) throw new DomainBadRequestException("exam id must be given ");

		$r = v::stringType()
			->notEmpty()
			->validate($data['content']??false);
		if(!$r) throw new DomainBadRequestException("Question content must be given");
		
		$r = v::intVal()
			->validate($data['is_active']??1);
		if(!$r) throw new DomainBadRequestException("question status must be given ");

	}
	
}