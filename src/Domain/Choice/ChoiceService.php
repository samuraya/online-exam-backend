<?php
declare(strict_types=1);

namespace App\Domain\Choice;

use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\Choice\Choice;
use App\Domain\DomainException\DomainBadRequestException;



use App\Domain\Question\QuestionNotFoundException;
use App\Infrastructure\Persistence\Choice\ChoiceRepository;
use \UnexpectedValueException;
use Respect\Validation\Validator as v;



final class ChoiceService 
{
    
    private $choiceRepository;
    

    public function __construct(ChoiceRepository $choiceRepository)
    {
        $this->choiceRepository = $choiceRepository;
        
    }

    public function fetch($question_id)
    {
        
        return $this->choiceRepository
            ->findByQuestion($question_id);      
        
    }

    public function persist($data,$question_id) 
    {

       $choices = [];
        foreach ($data as $choice) {
            $id = $choice['id'] ?? null;
            $questionId = $question_id;
            $content = $choice['content'];
            $status = $choice['is_correct']??false;
                   
            $choice = new Choice(
                $id,
                $questionId,
                $content,
                $status
            );

            $choice = $this->choiceRepository->save($choice);
            if(empty($choice)) return array();
                    
            $choice = $choice[0];
            $choices[]=$choice;
        }
        return $choices;
    }

    public function writeToChoice(Request $request)
    {
                            
        $data = $request->getParsedBody();
 
        $questionId = $request->getAttribute('questionId');

        $choices= self::persist($data,$questionId);
     
        $code = 200;
        return array('choices'=>$choices,'status_code'=>$code);

    }       


    public function fetchAllChoicesByQuestion($args)
    {
        $code = 422;
        $questionId = $args['questionId'];
       
        $choices = $this->choiceRepository
                ->findByQuestion($questionId);

        $code = 200;
        return array('choices'=>$choices,'status_code'=>$code);
    }

    public function deleteChoice(Request $request)
    {
        $choiceId = $request->getAttribute('choiceId') ?? false;

        $r = v::intVal()->validate($choiceId??false);
        if(!$r) throw new DomainBadRequestException("choice id must be integer");
           
        $this->choiceRepository->delete($choiceId);

        return array('result'=>'deleted','status_code'=>200);               

    }
   
}