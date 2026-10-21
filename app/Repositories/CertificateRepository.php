<?php

namespace App\Repositories;

use App\Models\Certificate;

class CertificateRepository implements IRepository
{
    public function create(array $data): Certificate
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

    public function delete($id): void
    {
        $certificate = $this->find($id);
        $certificate->delete();
    }

    public function find($id, $columns = ['*'])
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return Certificate::where($field, '=', $value)->get($columns);
    }

    public function all($columns = ['*'])
    {
        return Certificate::all();
    }
}
