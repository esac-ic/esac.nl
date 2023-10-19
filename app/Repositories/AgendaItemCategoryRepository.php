<?php

namespace App\Repositories;

use App\AgendaItemCategory;

class AgendaItemCategoryRepository implements IRepository
{
    public function create(array $data)
    {
        $agendaItemCategory = new AgendaItemCategory($data);
        $agendaItemCategory->save();

        return $agendaItemCategory;
    }

    public function update($id, array $data)
    {
        $agendaItemCategory = $this->find($id);
        $agendaItemCategory->update($data);

        return $agendaItemCategory;
    }

    public function delete($id)
    {
        $agendaItemCategory = $this->find($id);
        $agendaItemCategory->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return AgendaItemCategory::where($field, '=',$value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return AgendaItemCategory::all($columns);
    }
}