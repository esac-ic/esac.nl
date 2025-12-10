<?php

namespace App\Http\Requests;

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
        $rows = $this->all()['rows'];
        foreach ($rows as $rowKey => $row) {
            if ($row['type'] != 'select') continue;
            foreach($row['options'] as $optionKey => $option) {
                if (!empty($option['value'])) continue;
                $rows[$rowKey]['options'][$optionKey]['value'] = $option['name'];
            }
        }
        $this->replace(['rows' => $rows]);
    }
}
