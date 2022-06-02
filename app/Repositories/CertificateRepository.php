<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 19-3-2017
 * Time: 18:30
 */

namespace App\Repositories;


use App\Certificate;

class CertificateRepository implements IRepository
{
    private $_textRepository;

    public function __construct(TextRepository $textRepository)
    {
        $this->_textRepository = $textRepository;
    }

    public function create(array $data)
    {
        $text = $this->_textRepository->create($data);
        $Certificate = new Certificate($data);
        $Certificate->name = $text->id;
        $Certificate->duration = $data['duration'] === "" ? 0 : $data['duration'];
        $Certificate->save();

        return $Certificate;
    }

    public function update($id, array $data)
    {
        $data['duration'] = $data['duration'] === "" ? 0 : $data['duration'];
        $Certificate = $this->find($id);

        $Certificate->update($data);
        $this->_textRepository->update($Certificate->name, $data);

        return $Certificate;
    }

    public function delete($id)
    {
        $Certificate = $this->find($id);
        $Certificate->delete();
        $this->_textRepository->delete($Certificate->name);
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return Certificate::where($field, '=',$value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return Certificate::all();
    }
}