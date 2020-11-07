<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\BaseRepositoryInterface;
use App\Domain\DomainException\DomainBadRequestException;
use \PDO;




class BaseRepository implements BaseRepositoryInterface
{
    protected $table = 'base';
    
    protected $connection = false;
    protected $sql;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save($entity)  //returns a row that was written OR FALSE if failed
    {
        
        if($entity->getId() && $this->findById($entity->getId())){
            return $this->update($entity);
        }       
        return $this->insert($entity);
    }


    public function insert($entity) //returns last inserted row or false
    {
        
        $data = $entity->jsonSerialize();
       
        $fields = implode(',',array_keys($data));
        [$placeholders, $values] = $this->setPlaceholders($data);

        $sql = "INSERT INTO ".$this->table." ({$fields}) VALUES ({$placeholders})";
    

        $result = $this->flush($sql, $values);
   
        if($result===true){
            return $this->findById($this->connection->lastInsertId());
        }
        return false;
        
    }


    protected function flush($sql, $values, $where=''): bool
    {
        
        try {
            $stmt = $this->connection->prepare($sql);

            $stmt->execute($values);
            $success = TRUE;            
        
        } catch (\PDOException $e) {
            error_log(__METHOD__ . ':' . __LINE__ . ':'
            . $e->getMessage());
            $success = FALSE;
        } catch (\Throwable $e) {
            error_log(__METHOD__ . ':' . __LINE__ . ':'
            . $e->getMessage());
            $success = FALSE;
        }
        return $success;
    }

    public function create():void
    {

    }

    public function update($entity):array  //returns last updated row or false
    {
        $data = $entity->jsonSerialize();
        $fields = array_keys($data);
        [$placeholders, $values] = $this->setPlaceholders($data);

        $sets = array_map(
            function($field, $placeholder){
                return $field.' = '.$placeholder;
            }, 
            $fields, 
            explode(',', $placeholders)
        );


        $sql = 'UPDATE ' .$this->table.
            ' SET '.implode(',', $sets).
            ' WHERE id = ' . $entity->getId();
        $result = $this->flush($sql, $values);

        if($result===true){
           
            return $this->findById($entity->getId());
        }
        throw new DomainBadRequestException("Error while updating the record");  


   }

    public function delete($id)
    {
        $sql = "DELETE FROM ".$this->table." WHERE id = ?";

        $result = $this->flush($sql,array($id));
        return $result;
        
    }

    public function deleteWhere($column, $value):void
    {
    	
    }

    protected function setPlaceholders($data)
    {
        $placeholders = '';
        $values = [];

        foreach ($data as $key => $value) {
            $placeholders.='?,';
            $values[] = $value; 
        }
        $placeholders = rtrim($placeholders,',');

        return [$placeholders, $values];
    }



    public function findAll()   //all records from one table
    {
        $sql = 'SELECT * FROM ' . $this->table;
        
        $stmt = $this->connection->prepare($sql);           
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public function findById($id)  //all rows of certain id from one table
    {
        $sql = 'SELECT * FROM ' . $this->table.' WHERE id = ?';
 
        $stmt = $this->connection->prepare($sql);           
        $stmt->execute([$id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($rows!==false) return $rows;

        throw new DomainBadRequestException("Failed to find");
        
    }
}

