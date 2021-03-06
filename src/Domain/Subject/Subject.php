<?php
declare(strict_types=0);

namespace App\Domain\Subject;

use JsonSerializable;

class Subject implements JsonSerializable
{
   
    private $id;
    private $subjectId;
    private $name;
    private $instructorId;
     

    public function __construct(
            $id,
            $subjectId,
            $name,
            $instructorId
            
        )
    {
       $this->id = $id;
       $this->subjectId = $subjectId;
       $this->name = $name;
       $this->instructorId = $instructorId;
       
    }

    public function getId()
    {
        if($this->id) return (int) $this->id;
        return null;
    }    

    public function getSubjectId()
    {
        if($this->subjectId) return (int) $this->subjectId;
        return null;
    }    

    public function getInstructorId(): int
    {
        if($this->instructorId) return (string) $this->instructorId;
        return null;
    }   
     
    public function getName()
    {
       if($this->name) return (string) $this->name;
        return null;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'subject_id'=>$this->getSubjectId(),
            'name' => $this->getName(),
            'instructor_id'=>$this->getInstructorId(),
                        
        ];
    }    
    
}
