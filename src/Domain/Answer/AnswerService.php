<?php
declare(strict_types=1);

namespace App\Domain\Answer;

use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\Answer\Answer;
use App\Domain\Answer\AnswerNotFoundException;
use App\Infrastructure\Persistence\Answer\AnswerRepository;
use \UnexpectedValueException;



final class AnswerService 
{
    
    private $answerRepository;    

    public function __construct(AnswerRepository $answerRepository)
    {
        $this->answerRepository = $answerRepository;
        
    }

    public function writeToAnswer(Request $request)
    {
        $body = 'validation failed';
        $code = 422;
        
        $userId = $_SESSION['user_id'] ?? FALSE;
        if($userId) {
            $data = $request->getParsedBody();

            $answers = [];
            foreach ($data as $questionId=>$choiceId) {
                $id = $answer['id'] ?? null;          
                
                $answer = new Answer(
                    $id,
                    $userId,
                    $questionId,
                    $choiceId
                );              
            
                $answer = $this->answerRepository->save($answer);

                if($answer==false){  //nothing returned
                
                    $body = 'error creating answers';
                    return array('data'=>$body,'status_code'=>$code);
                }

                $answers[]=$answer;

            }           
                      
            $body = array('answers'=>$answers);
            $code = 200;
            return array('data'=>$body,'status_code'=>$code);

        }
        
        return array('data'=>$body,'status_code'=>$code);

    }


    public function fetchAllAnswersByQuestion($args)
    {
        
        $code = 422;
        $questionId = $args['questionId'];
                        
        try {
            $answers = $this->answerRepository
            ->findByQuestion($questionId);
        }
        catch(QuestionNotFoundException $e) {
            return array('error'=>$e->message,'status_code'=>$code);
        }
        
        $code = 200;
        return array('answers'=>$answers,'status_code'=>$code);        
        
        return array('data'=>$body,'status_code'=>$code);

    
    }
}