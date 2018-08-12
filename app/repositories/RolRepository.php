<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 16-2-2017
 * Time: 21:53
 */

namespace App\repositories;


use App\Rol;

class RolRepository implements IRepository
{
    private $_textRepository;

    public function __construct(TextRepository $textRepository)
    {
        $this->_textRepository = $textRepository;
    }

    public function create(array $data)
    {
        $text = $this->_textRepository->create($data);

        $rol = new Rol(["name" => $text->id]);
        $rol->save();

        return $rol;
    }

    public function update($id, array $data)
    {
        $rol = $this->find($id);
        $this->_textRepository->update($rol->name, $data);

        return $rol;
    }

    public function delete($id)
    {
        $rol = $this->find($id);
        $rol->delete();
        $this->_textRepository->delete($rol->name);

    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return Rol::where($field, '=',$value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return Rol::all($columns);
    }
}