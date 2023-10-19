<?php

namespace App\Repositories;

use App\Rol;

class RolRepository implements IRepository
{

    public function create(array $data)
    {
        $rol = Rol::create($data);
        return $rol;
    }

    public function update($id, array $data)
    {
        $rol = $this->find($id);
        $rol->update($data);

        return $rol;
    }

    public function delete($id)
    {
        $rol = $this->find($id);
        $rol->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return Rol::where($field, '=', $value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return Rol::all($columns);
    }
}
