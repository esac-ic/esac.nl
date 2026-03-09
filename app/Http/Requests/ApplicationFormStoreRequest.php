<?php

namespace App\Http\Requests;

use App\Models\ApplicationForm\ApplicationFormRow;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationFormStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'rows.*.name' => 'required',
            'rows.*.type' => 'required',
        ];
    }

    /**
     * Makes sure that rows.*.value isn't empty, uses rows.*.name as a fallback
     * 
     * @return void
     */
    protected function passedValidation(): void
    {
        $rows = $this->only(['rows'])['rows'];
        foreach ($rows as $rowKey => $row) {
            if (
                $row['type'] !== ApplicationFormRow::FORM_TYPE_SELECT &&
                $row['type'] !== ApplicationFormRow::FORM_TYPE_RADIO &&
                $row['type'] !== ApplicationFormRow::FORM_TYPE_MULTI_CHECK_BOX
            ) continue;
            if(!isset($row['options'])) continue;

            foreach($row['options'] as $optionKey => $option) {
                if (!empty($option['value'])) continue;
                $rows[$rowKey]['options'][$optionKey]['value'] = $option['name'];
            }
        }
        $this->merge(['rows' => $rows]);
    }
}
