<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 11-4-2017
 * Time: 22:09
 */

namespace App\repositories;


use App\ApplicationForm;
use App\ApplicationResponse;

class ApplicationFormRepository implements IRepository
{

    private $_textRepository;
    private $_applicationFormRowRepository;

    /**
     * AplicationFormRepository constructor.
     */
    public function __construct(TextRepository $textRepository, ApplicationFormRowRepository $applicationFormRowRepository)
    {
        $this->_textRepository = $textRepository;
        $this->_applicationFormRowRepository = $applicationFormRowRepository;
    }


    public function create(array $data)
    {
        $text = $this->_textRepository->create($data);

        $applicationForm = new ApplicationForm(["name" => $text->id]);
        $applicationForm->save();

        for ($i =0; $i <= $data['amount_of_formrows']; $i++){
            if(array_key_exists("NL_text_row_" . $i,$data)){
                $this->_applicationFormRowRepository->create($applicationForm->id,$data['row_type_'. $i],$data['NL_text_row_'. $i],$data['EN_text_row_'. $i],array_key_exists('row_required_'. $i,$data));
            }
        }

        return $applicationForm;
    }

    public function update($id, array $data)
    {
        $applicationForm = $this->find($id);
        $this->_textRepository->update($applicationForm->name, $data);

        foreach ($applicationForm->getApplicationFormRows as $row){
            $this->_applicationFormRowRepository->delete($row->id);
        }

        for ($i =0; $i <= $data['amount_of_formrows']; $i++){
            if(array_key_exists("NL_text_row_" . $i,$data)){
                $this->_applicationFormRowRepository->create($applicationForm->id,$data['row_type_'. $i],$data['NL_text_row_'. $i],$data['EN_text_row_'. $i],array_key_exists('row_required_'. $i,$data));
            }
        }

        return $applicationForm;
    }

    public function delete($id)
    {
        $applicationForm = $this->find($id);
        foreach ($applicationForm->getApplicationFormRows as $row){
            $this->_applicationFormRowRepository->delete($row->id);
        }
        $applicationForm->delete();
        $this->_textRepository->delete($applicationForm->name);
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id',$id,$columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return ApplicationForm::query()->where($field,'=',$value)->first($columns);
    }

    public function all($columns = array('*'))
    {
        return ApplicationForm::all($columns);
    }

}