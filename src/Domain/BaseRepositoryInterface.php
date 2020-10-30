<?php
declare(strict_types=1);

namespace App\Domain;


interface BaseRepositoryInterface
{
   
    public function insert($entity);

    public function create();

    public function update($entity);

    public function delete($id);

    public function deleteWhere($column, $value);
}