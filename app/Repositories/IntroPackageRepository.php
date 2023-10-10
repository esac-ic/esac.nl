<?php

namespace App\Repositories;

use App\IntroPackage;
use Carbon\Carbon;

class IntroPackageRepository implements IRepository
{
    public function create(array $data)
    {
        $data['deadline'] = Carbon::createFromFormat('d-m-Y', $data['deadline']);
        $package = IntroPackage::create($data);

        return $package;
    }

    public function update($id, array $data)
    {
        $package = $this->find($id);
        $data['deadline'] = Carbon::createFromFormat('d-m-Y', $data['deadline']);
        $package->update($data);

        return $package;
    }

    public function delete($id)
    {
        $package = $this->find($id);
        $package->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return IntroPackage::where($field, '=', $value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return IntroPackage::all($columns);
    }
}
