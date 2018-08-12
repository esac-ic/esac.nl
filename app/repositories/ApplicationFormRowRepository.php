<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 14-4-2017
 * Time: 16:17
 */

namespace App\repositories;


use App\ApplicationFormRow;

class ApplicationFormRowRepository
{
    private $_textRepository;

    public function __construct(TextRepository $textRepository)
    {
        $this->_textRepository = $textRepository;
    }

    public function create($formid, $type,$nameNL, $nameEN, $required)
    {
        $text = $this->_textRepository->create(['NL_text' => $nameNL, 'EN_text' => $nameEN]);
        $row = new ApplicationFormRow();
        $row->type = $type;
        $row->name = $text->id;
        $row->application_form_id = $formid;
        $row->required = $required;
        $row->save();
        return $row;
    }

    public function delete($id)
    {
        $applicationFormRow = $this->find($id);
        if($applicationFormRow != null){
            $applicationFormRow->delete();
            $this->_textRepository->delete($applicationFormRow->name);
        }
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return ApplicationFormRow::where($field, '=',$value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return ApplicationFormRow::all($columns);
    }
    public function getRows($application_form_id) {
        return $this->findBy('application_form_id', $application_form_id, $columns = array('*'));

    }
}