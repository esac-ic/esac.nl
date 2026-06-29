<?php

namespace App\Repositories;

use App\Models\Committee;
use App\Models\User;
use App\Repositories\IRepository;

class CommitteeRepository implements IRepository
{
    
    public function create(array $data)
    {
        return Committee::create($data);
    }
    
    public function update($id, array $data)
    {
        return $this->find($id)->update($data);
    }
    
    public function delete($id): void
    {
        $this->find($id)->delete();
    }
    
    public function find($id, $columns = array('*')): ?Committee
    {
        return $this->findBy('id', $id, $columns);
    }
    
    public function findBy($field, $value, $columns = array('*')): ?Committee
    {
        return Committee::where($field, $value)->first($columns);
    }
    
    public function all($columns = array('*'))
    {
        return Committee::all($columns);
    }
}