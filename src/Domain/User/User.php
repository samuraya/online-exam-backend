<?php
declare(strict_types=0);

namespace App\Domain\User;

use JsonSerializable;

class User implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

     /**
     * @var string
     */
    private $password;

    private $fullName;



    /**
     * @param int|null  $id
     * @param string    $username
     * @param string    $firstName
     * @param string    $lastName
     * @param string    $password
     */
    public function __construct(
            $id,
            $userId, 
            $password,
            $level = 0            
        )
    {  
        
        $this->userId = $userId;
        $this->password = $password;
        $this->id = $id;
        $this->level = $level;       
        
    }
    public function getLevel()
    {
        if($this->level!==null) return (int) $this->level;
        return 0;
    }
    public function getId()
    {
        if($this->id!==null) return (int) $this->id;
        return null;
    }
    public function getUserId()
    {
        if($this->userId!==null) return (int) $this->userId;
        return null;
    }
   
    public function getPassword()
    {
        if($this->password!==null) return (string) $this->password;
        return null;
    }

      
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'user_id'=>$this->getUserId(),
            'password'=> $this->getPassword(),
            'level'=>$this->getLevel()
        ];
    }   
}
