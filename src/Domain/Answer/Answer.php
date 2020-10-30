<?php
declare(strict_types=0);

namespace App\Domain\Answer;

use JsonSerializable;

class Answer implements JsonSerializable
{
   
    private $id;
    private $userId;
    private $questionId;
    private $choiceId;
    //private $isCorrect;

         

    public function __construct(
            $id,
            $userId,
            $questionId,
            $choiceId
           // $isCorrect,
        )
    {
       $this->id = $id;
       $this->userId = $userId;
       $this->questionId = $questionId;
       $this->choiceId = $choiceId;
       //$this->status = $status;
    }

    public function getId()
    {
        if($this->id) return (int) $this->id;
        return null;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getQuestionId(): int
    {
        if($this->questionId) return (int) $this->questionId;
        return null;
    }

    public function setQuestionId(int $questionId) 
    {
        $this->questionId = $questionId;
    }
     

    public function getUserId()
    {
        if($this->userId) return (int) $this->userId;
        return null;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }


     public function getChoiceId()
    {
        if($this->choiceId) return (int) $this->choiceId;
        return null;
    }

    public function setChoiceId(int $choiceId)
    {
        $this->choiceId = $choiceId;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'user_id'=>$this->getUserId(),
            'question_id'=>$this->getQuestionId(),
            'choice_id'=>$this->getChoiceId(),
            
        ];
    }

    public function unset($prop):void
    {
       // echo $this->$prop;
        $this->$prop = null;
    }
    
}
