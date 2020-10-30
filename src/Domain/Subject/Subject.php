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

    // public function setId(int $id)
    // {
    //     $this->id = $id;
    // }

    public function getSubjectId()
    {
        if($this->subjectId) return (int) $this->subjectId;
        return null;
    }

    // public function setSubjectId(int $subjectId)
    // {
    //     $this->subjectId = $subjectId;
    // }

    public function getInstructorId(): int
    {
        if($this->instructorId) return (string) $this->instructorId;
        return null;
    }

    // public function setInstructorId(string $instructorId) 
    // {
    //     $this->instructorId = $instructorId;
    // }
     
    public function getName()
    {
       if($this->name) return (string) $this->name;
        return null;
    }

    // public function setName(string $name)
    // {
    //     $this->name = $name;
    // }
   

    // public function setStatus(string $status)
    // {
    //     if($this->status) return (string) $this->status;
    //     return null;
    // }




    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'subject_id'=>$this->getSubjectId(),
            'name' => $this->getName(),
            'instructor_id'=>$this->getInstructorId(),
            // 'datetime'=>$this->getDateTime(),
            // 'is_active'=>$this->getStatus()
            
        ];
    }    
    
}
