<?php

namespace App\Repositories;

use App\Models\Committee;
use App\Repositories\IRepository;

class CommitteeRepository implements IRepository
{
    
    public function create(array $data)
    {
        $committee = Committee::create($data);
        $committee->save();
        return $committee;
    }
    
    public function update($id, array $data)
    {
        $committee = $this->find($id);
        $committee->update($data);
        return $committee;
    }
    
    public function delete($id): void
    {
        $this->find($id)->delete();
    }
    
    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }
    
    public function findBy($field, $value, $columns = array('*'))
    {
        return Committee::where($field, '=', $value)->get($columns);
    }
    
    public function all($columns = array('*'))
    {
        $committees = Committee::all();
        return $committees;
    }
}