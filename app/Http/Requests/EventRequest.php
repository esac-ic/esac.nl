<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class EventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:255'],
            'short_description' => ['required', 'max:100'],
            'body' => ['required'],
            'image' => ['nullable', File::image()->max('10mb')],
            'event_category_id' => ['required', 'exists:event_categories,id'],
            'registration_form_id' => ['nullable', Rule::exists('registration_forms', 'id')->withoutTrashed()],
            'registration_deadline' => ['exclude_without:registration_form_id', 'required', 'date'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'shows_certificates' => ['boolean'],
            'copy_of' => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return ['event_category_id' => 'category'];
    }

    /**
     * An unchecked checkbox isn't posted.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['shows_certificates' => $this->boolean('shows_certificates')]);
    }
}
