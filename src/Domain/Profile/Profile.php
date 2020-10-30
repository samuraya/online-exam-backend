<?php
declare(strict_types=0);

namespace App\Domain\Profile;

use JsonSerializable;

class Profile implements JsonSerializable
{
    
    private $id;   
    private $firstName; 
    private $lastName;
    private $email;

    
    public function __construct(
            $id, 
            $firstName,
            $lastName,
            $email           
        )
    {  
        
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;       
        
    }
    public function getId()
    {
        return $this->id;   
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function getLastName()
    {
        return $this->lastName;
    }
   
    public function getEmail()
    {
       return $this->email;
    }

      
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'first_name'=>$this->getFirstName(),
            'last_name'=> $this->getLastName(),
            'email'=>$this->getEmail()
        ];
    }   
}
