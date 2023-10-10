<?php

namespace App\Repositories;

use App\Certificate;

class CertificateRepository implements IRepository
{
    public function create(array $data)
    {
        $certificate = new Certificate($data);
        $certificate->save();

        return $certificate;
    }

    public function update($id, array $data)
    {
        $certificate = $this->find($id);
        $certificate->update($data);

        return $certificate;
    }

    public function delete($id)
    {
        $certificate = $this->find($id);
        $certificate->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return Certificate::where($field, '=', $value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return Certificate::all();
    }
}
