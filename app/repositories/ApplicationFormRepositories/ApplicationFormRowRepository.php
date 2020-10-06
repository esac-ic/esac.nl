<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 14-4-2017
 * Time: 16:17
 */

namespace App\repositories\ApplicationFormRepository;

use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationFormRowOption;
use App\repositories\TextRepository;

/**
 * Class ApplicationFormRowRepository
 * @package App\repositories
 */
class ApplicationFormRowRepository
{
    /**
     * @var TextRepository
     */
    private $textRepository;

    /**
     * @var ApplicationFormRowOptionRepository
     */
    private $applicationFormRowOptionRepository;

    /**
     * ApplicationFormRowRepository constructor.
     * @param TextRepository $textRepository
     * @param ApplicationFormRowOptionRepository $applicationFormRowOptionRepository
     */
    public function __construct(TextRepository $textRepository, ApplicationFormRowOptionRepository $applicationFormRowOptionRepository)
    {
        $this->textRepository = $textRepository;
        $this->applicationFormRowOptionRepository = $applicationFormRowOptionRepository;
    }

    /**
     * @param int $formId
     * @param array $data
     */
    public function create(int $formId, array $data): ApplicationFormRow
    {
        $text = $this->textRepository->create(['NL_text' => $data['nl_name'], 'EN_text' => $data['en_name']]);
        $row = new ApplicationFormRow($data);
        $row->name = $text->id;
        $row->application_form_id = $formId;
        $row->required = array_key_exists('required', $data);
        $row->save();

        if (array_key_exists('options', $data)) {
            foreach ($data['options'] as $optionData) {
                $this->applicationFormRowOptionRepository->create($row->id, $optionData);
            }
        }

        return $row;
    }

    /**
     * @param int $id
     * @param array $data
     */
    public function update(int $id, array $data): void
    {
        $row = $this->find($id);
        $this->textRepository->update($row->name, [
            'NL_text' => $data['nl_name'],
            'EN_text' => $data['en_name']
        ]);

        $row->type = $data['type'];
        $row->required = array_key_exists('required', $data);
        $row->save();

        $optionIds = [];

        if (array_key_exists('options', $data)) {
            foreach ($data['options'] as $optionData) {
                if(array_key_exists('id', $optionData)) {
                    $option = $this->applicationFormRowOptionRepository->update($optionData['id'], $optionData);
                } else {
                    $option = $this->applicationFormRowOptionRepository->create($row->id, $optionData);
                }
                $optionIds[] = $option->id;
            }
        }

        ApplicationFormRowOption::query()
            ->whereNotIn('id', $optionIds)
            ->where('application_form_row_id', $row->id)
            ->delete();
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $applicationFormRow = $this->find($id);
        if ($applicationFormRow != null) {
            $applicationFormRow->delete();
            $this->textRepository->delete($applicationFormRow->name);
        }
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
        return ApplicationFormRow::where($field, '=', $value)->get($columns);
    }

    /**
     * @param array $columns
     * @return ApplicationFormRow[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = array('*'))
    {
        return ApplicationFormRow::all($columns);
    }

    /**
     * @param $application_form_id
     * @return mixed
     */
    public function getRows($application_form_id)
    {
        return $this->findBy('application_form_id', $application_form_id, $columns = array('*'));

    }
}