<?php

namespace App\repositories;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;

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
        $this->textRepository->update($applicationForm->name, [
            'NL_text' => $data['nl_name'],
            'EN_text' => $data['en_name']
        ]);

        $applicationFormRowIds = [];

        if(array_key_exists('rows', $data) === true) {
            foreach ($data['rows'] as $rowData) {
                if(array_key_exists('id', $rowData) === true) {
                    $this->applicationFormRowRepository->update($rowData['id'], $rowData);
                    $applicationFormRowIds[] = $rowData['id'];
                } else {
                    $applicationFormRow = $this->applicationFormRowRepository->create($applicationForm->id, $rowData);
                    $applicationFormRowIds[] = $applicationFormRow->id;
                }
            }
        }

        ApplicationFormRow::query()
            ->where('application_form_id', $applicationForm->id)
            ->whereNotIn('id', $applicationFormRowIds)
            ->delete();

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