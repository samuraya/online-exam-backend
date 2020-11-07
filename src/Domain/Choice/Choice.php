<?php
declare(strict_types=0);

namespace App\Domain\Choice;

use JsonSerializable;

class Choice implements JsonSerializable
{
   
    private $id;
    private $questionId;
    private $content;
    private $isCorrect;         

    public function __construct(
            $id,
            $questionId,
            $content,
            $isCorrect
        )
    {
       $this->id = $id;
       $this->questionId = $questionId;
       $this->content = $content;
       $this->isCorrect = $isCorrect;
    }

    public function getId()
    {
        if($this->id!==null) return (int) $this->id;
        return null;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getQuestionId(): int
    {
        if($this->questionId!==null) return (int) $this->questionId;
        return null;
    }

    public function setQuestionId(int $questionId) 
    {
        $this->questionId = $questionId;
    }
     
    public function getContent()
    {
       if($this->content!==null) return (string) $this->content;
        return null;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getStatus():int
    {
        if($this->isCorrect==1) return 1;
        return 0;
    }

    public function setStatus(int $isCorrect)
    {
       $this->isCorrect=$isCorrect;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'question_id'=>$this->getQuestionId(),
            'content'=>$this->getContent(),
            'is_correct'=>$this->getStatus()
            
        ];
    }

    public function unset($prop):void
    {
       
        $this->$prop = null;
    }
    
}
