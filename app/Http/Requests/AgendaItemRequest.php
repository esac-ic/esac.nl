<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgendaItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:255'],
            'short_description' => ['required', 'max:100'],
            'text' => ['required'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
            'category' => ['required', 'exists:agenda_item_categories,id'],
            'applicationForm' => ['nullable', Rule::exists('application_forms', 'id')->withoutTrashed()],
            'subscription_end_date' => ['exclude_without:applicationForm', 'required', 'date'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'climbing_activity' => ['boolean'],
        ];
    }

    /**
     * An unchecked checkbox isn't posted.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['climbing_activity' => $this->boolean('climbing_activity')]);
    }
}
