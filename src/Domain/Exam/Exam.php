<?php
declare(strict_types=0);

namespace App\Domain\Exam;

use JsonSerializable;

class Exam implements JsonSerializable
{
   
    private $id;
    private $name;
    private $subjectId;
    private $dateTime;
    private $status;     

    public function __construct(
            $id,
            $name,
            $subjectId,
            $dateTime,
            $status
        )
    {
       $this->id = $id;
       $this->name = $name;
       $this->subjectId = $subjectId;
       $this->dateTime = $dateTime;
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

    public function getSubjectId(): int
    {
        if($this->subjectId) return (string) $this->subjectId;
        return null;
    }

    public function setSubjectId(string $subjectId) 
    {
        $this->subjectId = $subjectId;
    }
     
    public function getName()
    {
       if($this->name) return (string) $this->name;
        return null;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

     public function getDateTime()
    {
    
        return date("Y-m-d H:i:s",strtotime($this->dateTime));
    }

    public function setDateTime(string $dateTime)
    {
        $this->dateTime = new DateTime($dateTime);
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
            'name' => $this->getName(),
            'subject_id'=>$this->getSubjectId(),
            'datetime'=>$this->getDateTime(),
            'is_active'=>$this->getStatus()
            
        ];
    }
    
    public function unset($prop):void
    {
       $this->$prop = null;
    }
    
}
