<?php

namespace App\Http\Requests;

use App\Enums\FormFieldType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A registration form with its fields, which the Vue form builder posts as rows.
 */
class RegistrationFormRequest extends FormRequest
{
    public function rules(): array
    {
        $typesWithOptions = collect(FormFieldType::cases())->filter->hasOptions()->pluck('value')->implode(',');

        return [
            'name' => ['required'],
            'rows' => ['array'],
            'rows.*.id' => ['sometimes', 'integer'],
            'rows.*.name' => ['required'],
            'rows.*.type' => ['required', Rule::enum(FormFieldType::class)],
            'rows.*.required' => ['boolean'],
            'rows.*.options' => ['array', "required_if:rows.*.type,$typesWithOptions"],
            'rows.*.options.*.id' => ['sometimes', 'integer'],
            'rows.*.options.*.name' => ['required'],
            'rows.*.options.*.value' => ['required'],
        ];
    }

    /**
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
     * A checked checkbox is posted as "on" and an unchecked one isn't posted; an option without a value uses its name.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['rows' => collect($this->input('rows', []))->map(fn (array $field, $key) => [
            ...$field,
            'required' => $this->boolean("rows.$key.required"),
            'options' => collect($field['options'] ?? [])->map(fn (array $option) => [
                ...$option,
                'value' => filled($option['value'] ?? null) ? $option['value'] : ($option['name'] ?? null),
            ])->all(),
        ])->all()]);
    }
}
