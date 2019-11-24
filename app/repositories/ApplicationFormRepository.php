<?php

namespace App\repositories;

use App\Models\ApplicationForm\ApplicationForm;

class ApplicationFormRepository implements IRepository
{
    private $textRepository;
    private $applicationFormRowRepository;

    /**
     * AplicationFormRepository constructor.
     * @param TextRepository $textRepository
     * @param ApplicationFormRowRepository $applicationFormRowRepository
     */
    public function __construct(TextRepository $textRepository, ApplicationFormRowRepository $applicationFormRowRepository)
    {
        $this->textRepository = $textRepository;
        $this->applicationFormRowRepository = $applicationFormRowRepository;
    }

    public function create(array $data)
    {
        $text = $this->textRepository->create([
            'NL_text' => $data['nl_name'],
            'EN_text' => $data['en_name']
        ]);

        $applicationForm = new ApplicationForm(["name" => $text->id]);
        $applicationForm->save();

        if(array_key_exists('rows', $data) === true) {
            foreach ($data['rows'] as $rowData) {
                $this->applicationFormRowRepository->create($applicationForm->id, $rowData);
            }
        }

        return $applicationForm;
    }

    public function update($id, array $data)
    {
        $applicationForm = $this->find($id);
        $this->_textRepository->update($applicationForm->name, $data);

        foreach ($applicationForm->applicationFormRows as $row){
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
        foreach ($applicationForm->applicationFormRows as $row){
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