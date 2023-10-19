<?php

namespace App\Repositories\ApplicationFormRepositories;

use App\Models\ApplicationForm\ApplicationFormRowOption;

class ApplicationFormRowOptionRepository
{
    public function create(int $applicationFormRowId, array $data): ApplicationFormRowOption
    {
        $option = new ApplicationFormRowOption($data);
        $option->application_form_row_id = $applicationFormRowId;
        $option->save();

        return $option;
    }

    public function update($id, array $data)
    {
        $option = $this->find($id);
        $option->update($data);
        $option->save();

        return $option;
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = array('*'))
    {
        return ApplicationFormRowOption::where($field, '=', $value)->get($columns);
    }
}
