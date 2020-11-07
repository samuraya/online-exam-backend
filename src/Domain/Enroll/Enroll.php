<?php
declare(strict_types=0);

namespace App\Domain\Enroll;

use JsonSerializable;

class Enroll implements JsonSerializable
{
   
    private $id;
    private $subjectId;
    private $status;     

    public function __construct(
            $studentId,
            $subjectId,
            $status = 1
        )
    {
       $this->id = $studentId;
       $this->subjectId = $subjectId;
       $this->status = $status;
    }

    public function getId()
    {
        if($this->id) return (int) $this->id;
        return null;
    }

    public function setId(int $studentId)
    {
        $this->id = $studentId;
    }

    public function getSubjectId()
    {
        if($this->subjectId) return (int) $this->subjectId;
        return null;
    }

    public function setSubjectId($subjectId) 
    {
        $this->subjectId = (int) $subjectId;
    }
         
    public function getStatus()
    {
        if($this->status) return (int) $this->status;
        return null;
    }

    public function setStatus($status)
    {
        $this->status = (int) $status;
    }

    public function jsonSerialize()
    {
        return [
            'student_id' => $this->getId(),
            // 'student_id' => $this->getName(),
            'subject_id'=>$this->getSubjectId(),
            'is_active'=>$this->getStatus()
            
        ];
    }

    public function unset($prop):void
    {
       
        $this->$prop = null;
    }
    
}
