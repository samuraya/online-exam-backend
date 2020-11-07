<?php
declare(strict_types=0);

namespace App\Domain\Question;

use JsonSerializable;

class Question implements JsonSerializable
{
   
    private $id;
    private $examId;
    private $content;
    private $status;         

    public function __construct(
            $id,
            $examId,
            $content,
            $status
        )
    {
       $this->id = $id;
       $this->examId = $examId;
       $this->content = $content;
       $this->status = $status;
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

    public function getExamId(): int
    {
        if($this->examId) return (int) $this->examId;
        return null;
    }

    public function setExamId(int $examId) 
    {
        $this->examId = $examId;
    }
     
    public function getContent()
    {
       if($this->content) return (string) $this->content;
        return null;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getStatus():string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        if($this->status) return (string) $this->status;
        return null;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'exam_id'=>$this->getExamId(),
            'content'=>$this->getContent(),
            'is_active'=>$this->getStatus()
            
        ];
    }

    public function unset($prop):void
    {
       
        $this->$prop = null;
    }
    
}
