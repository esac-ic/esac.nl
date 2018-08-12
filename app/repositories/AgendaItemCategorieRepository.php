<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 9-4-2017
 * Time: 17:43
 */

namespace App\repositories;


use App\AgendaItemCategorie;

class AgendaItemCategorieRepository implements IRepository
{
    private $_textRepository;

    public function __construct(TextRepository $textRepository)
    {
        $this->_textRepository = $textRepository;
    }

    public function create(array $data)
    {
        $text = $this->_textRepository->create($data);

        $agendaItemCategory = new AgendaItemCategorie(["name" => $text->id]);
        $agendaItemCategory->save();

        return $agendaItemCategory;
    }

    public function update($id, array $data)
    {
        $agendaItemCategory = $this->find($id);
        $this->_textRepository->update($agendaItemCategory->name, $data);

        return $agendaItemCategory;
    }

    public function delete($id)
    {
        $agendaItemCategory = $this->find($id);
        $agendaItemCategory->delete();
        $this->_textRepository->delete($agendaItemCategory->name);

    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return AgendaItemCategorie::where($field, '=',$value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return AgendaItemCategorie::all($columns);
    }
}