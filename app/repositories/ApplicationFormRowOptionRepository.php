<?php
/**
 * Created by PhpStorm.
 * User: Niek
 * Date: 7-1-2020
 * Time: 20:07
 */

namespace App\repositories;


use App\Models\ApplicationForm\ApplicationFormRowOption;

class ApplicationFormRowOptionRepository
{
    /**
     * @var TextRepository
     */
    private $textRepository;

    /**
     * ApplicationFormRowRepository constructor.
     * @param TextRepository $textRepository
     */
    public function __construct(TextRepository $textRepository)
    {
        $this->textRepository = $textRepository;
    }

    public function create(int $applicationFormRowId, array $data): ApplicationFormRowOption
    {
        $text = $this->textRepository->create(['NL_text' => $data['nl_name'], 'EN_text' => $data['en_name']]);

        $option = new ApplicationFormRowOption($data);
        $option->application_form_row_id = $applicationFormRowId;
        $option->name_id = $text->id;
        $option->save();

        return $option;
    }

    public function update($id, array $data)
    {
        $option = $this->find($id);
        $this->textRepository->update($option->name_id, [
            'NL_text' => $data['nl_name'],
            'EN_text' => $data['en_name']
        ]);

        $option->value = $data['value'];
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