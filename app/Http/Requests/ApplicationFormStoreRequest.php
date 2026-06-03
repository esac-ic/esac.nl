<?php

namespace App\Http\Requests;

use App\Models\ApplicationForm\ApplicationFormRow;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationFormStoreRequest extends FormRequest
{
    private const TYPES_WITH_OPTIONS = [
        ApplicationFormRow::FORM_TYPE_SELECT,
        ApplicationFormRow::FORM_TYPE_RADIO,
        ApplicationFormRow::FORM_TYPE_MULTI_CHECK_BOX,
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $typesWithOptions = implode(',', self::TYPES_WITH_OPTIONS);

        return [
            'name' => 'required',
            'rows.*.name' => 'required',
            'rows.*.type' => 'required',
            'rows.*.options' => "array|required_if:rows.*.type,$typesWithOptions",
            'rows.*.options.*.name' => 'required',
            'rows.*.options.*.value' => 'nullable',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'rows.*.options.required_if' => 'Every - select, radio or multi checkbox - must have at least one selectable option',
            'rows.*.options.*.name.required' => 'Every option of a - select, radio or multi checkbox - must have a name',
        ];
    }

    /**
     * Makes sure that rows.*.options.*.value isn't empty, uses rows.*.options.*.name as a fallback
     * 
     * @return void
     */
    protected function passedValidation(): void
    {
        $rows = $this->input('rows', []);
        foreach ($rows as $rowKey => $row) {
            if (! in_array($row['type'], self::TYPES_WITH_OPTIONS) || ! isset($row['options'])) {
                continue;
            }

            foreach($row['options'] as $optionKey => $option) {
                if (!empty($option['value'])) continue;
                $rows[$rowKey]['options'][$optionKey]['value'] = $option['name'];
            }
        }
        $this->merge(['rows' => $rows]);
    }
}
