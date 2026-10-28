<?php

namespace App\Repositories;

use App\Models\Rol;

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

    public function delete($id): void
    {
        $rol = $this->find($id);
        $rol->delete();
    }

    public function find($id, $columns = ['*'])
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return Rol::where($field, '=', $value)->get($columns);
    }

    public function all($columns = ['*'])
    {
        return Rol::all($columns);
    }
}
