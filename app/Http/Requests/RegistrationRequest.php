<?php

namespace App\Http\Requests;

use App\Enums\FormFieldType;
use App\Models\Event;
use App\Models\RegistrationFormField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * The answers to an event's registration form, keyed by the field id.
 */
class RegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        $fields = $this->event()->registrationForm?->fields()->with('options')->get() ?? [];

        $rules = [];
        foreach ($fields as $field) {
            $rules += $this->rulesFor($field);
        }

        return $rules;
    }

    protected function event(): Event
    {
        return $this->route('event');
    }

    private function rulesFor(RegistrationFormField $field): array
    {
        $presence = $field->required ? 'required' : 'nullable';
        $options = Rule::in($field->options->pluck('value')->all());

        return match ($field->type) {
            FormFieldType::Number => [$field->id => [$presence, 'numeric']],
            FormFieldType::Checkbox => [$field->id => $field->required ? ['accepted'] : ['nullable', Rule::in(['on', '0'])]],
            FormFieldType::Select => [$field->id => [$presence, $options]],
            // Radio buttons and checkboxes are posted as arrays.
            FormFieldType::Radio => [$field->id => [$presence, 'array', 'max:1'], $field->id.'.*' => [$options]],
            FormFieldType::Checkboxes => [$field->id => [$presence, 'array'], $field->id.'.*' => [$options]],
            default => [$field->id => [$presence, 'string']],
        };
    }
}
