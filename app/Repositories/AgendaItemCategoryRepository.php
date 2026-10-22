<?php

namespace App\Repositories;

use App\Models\AgendaItemCategory;

class AgendaItemCategoryRepository implements IRepository
{
    public function create(array $data): AgendaItemCategory
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

    public function delete($id): void
    {
        $agendaItemCategory = $this->find($id);
        $agendaItemCategory->delete();
    }

    public function find($id, $columns = ['*'])
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return AgendaItemCategory::where($field, '=',$value)->get($columns);
    }

    public function all($columns = ['*'])
    {
        return AgendaItemCategory::all($columns);
    }
}