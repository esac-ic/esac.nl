<?php

namespace App\Repositories;


use App\IntroPackage;
use Carbon\Carbon;

class IntroPackageRepository implements IRepository
{
    private $_textRepository;

    public function __construct(TextRepository $textRepository)
    {
        $this->_textRepository = $textRepository;
    }

    public function create(array $data)
    {
        $text = $this->_textRepository->create($data);

        $data['deadline'] = Carbon::createFromFormat('d-m-Y', $data['deadline']);

        $package = new IntroPackage($data);
        $package->name = $text->id;
        $package->save();

        return $package;
    }

    public function update($id, array $data)
    {
        $package = $this->find($id);

        $data['deadline'] = Carbon::createFromFormat('d-m-Y', $data['deadline']);

        $package->update($data);
        $this->_textRepository->update($package->name, $data);

        return $package;
    }

    public function delete($id)
    {
        $package = $this->find($id);
        $package->delete();
        $this->_textRepository->delete($package->name);

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
