<?php

namespace App\repositories\ApplicationFormRepository;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\repositories\IRepository;
use App\repositories\TextRepository;

/**
 * Class ApplicationFormRepository
 * @package App\repositories\ApplicationFormRepository
 */
class ApplicationFormRepository implements IRepository
{
    /**
     * @var TextRepository
     */
    private $textRepository;
    /**
     * @var ApplicationFormRowRepository
     */
    private $applicationFormRowRepository;

    /**
     * AplicationFormRepository constructor.
     * @param TextRepository $textRepository
     * @param ApplicationFormRowRepository $applicationFormRowRepository
     */
    public function __construct(
        TextRepository $textRepository,
        ApplicationFormRowRepository $applicationFormRowRepository
    ) {
        $this->textRepository               = $textRepository;
        $this->applicationFormRowRepository = $applicationFormRowRepository;
    }

    /**
     * @param array $data
     * @return ApplicationForm
     */
    public function create(array $data): ApplicationForm
    {
        $text = $this->textRepository->create([
            'NL_text' => $data['nl_name'],
            'EN_text' => $data['en_name']
        ]);

        $applicationForm = new ApplicationForm(["name" => $text->id]);
        $applicationForm->save();

        if (array_key_exists('rows', $data) === true) {
            foreach ($data['rows'] as $rowData) {
                $this->applicationFormRowRepository->create($applicationForm->id, $rowData);
            }
        }

        return $applicationForm;
    }

    /**
     * @param $id
     * @param array $data
     * @return ApplicationForm
     */
    public function update($id, array $data): ApplicationForm
    {
        $applicationForm = $this->find($id);
        $this->textRepository->update($applicationForm->name, [
            'NL_text' => $data['nl_name'],
            'EN_text' => $data['en_name']
        ]);

        $applicationFormRowIds = [];

        if (array_key_exists('rows', $data) === true) {
            foreach ($data['rows'] as $rowData) {
                if (array_key_exists('id', $rowData) === true) {
                    $this->applicationFormRowRepository->update($rowData['id'], $rowData);
                    $applicationFormRowIds[] = $rowData['id'];
                } else {
                    $applicationFormRow      = $this
                        ->applicationFormRowRepository
                        ->create($applicationForm->id, $rowData);
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

    /**
     * @param $id
     * @throws \Exception
     */
    public function delete($id): void
    {
        $applicationForm = $this->find($id);
        foreach ($applicationForm->applicationFormRows as $row) {
            $this->applicationFormRowRepository->delete($row->id);
        }
        $applicationForm->delete();
        $this->textRepository->delete($applicationForm->name);
    }

    /**
     * @param $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns);
    }

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function findBy($field, $value, $columns = array('*'))
    {
        return ApplicationForm::query()->where($field, '=', $value)->first($columns);
    }

    /**
     * @param array $columns
     * @return ApplicationForm[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = array('*'))
    {
        return ApplicationForm::all($columns);
    }

}