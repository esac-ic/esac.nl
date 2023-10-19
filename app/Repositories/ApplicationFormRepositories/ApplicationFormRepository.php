<?php

namespace App\Repositories\ApplicationFormRepositories;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Repositories\IRepository;

/**
 * Class ApplicationFormRepository
 * @package App\Repositories\ApplicationFormRepository
 */
class ApplicationFormRepository implements IRepository
{
    /**
     * @var ApplicationFormRowRepository
     */
    private $applicationFormRowRepository;

    /**
     * AplicationFormRepository constructor.
     * @param ApplicationFormRowRepository $applicationFormRowRepository
     */
    public function __construct(ApplicationFormRowRepository $applicationFormRowRepository
    ) {
        $this->applicationFormRowRepository = $applicationFormRowRepository;
    }

    /**
     * @param array $data
     * @return ApplicationForm
     */
    public function create(array $data): ApplicationForm
    {
        $applicationForm = new ApplicationForm($data);
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
        $applicationForm->update($data);

        $applicationFormRowIds = [];

        if (array_key_exists('rows', $data) === true) {
            foreach ($data['rows'] as $rowData) {
                if (array_key_exists('id', $rowData) === true) {
                    $this->applicationFormRowRepository->update($rowData['id'], $rowData);
                    $applicationFormRowIds[] = $rowData['id'];
                } else {
                    $applicationFormRow = $this
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
