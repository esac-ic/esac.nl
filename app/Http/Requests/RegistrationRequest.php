<?php

namespace App\Http\Requests;

use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationFormRow;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * The answers to an event's registration form, keyed by the form row id.
 */
class RegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        $rows = $this->agendaItem()->getApplicationForm?->applicationFormRows()->with('applicationFormRowOptions')->get() ?? [];

        $rules = [];
        foreach ($rows as $row) {
            $rules += $this->rulesFor($row);
        }

        return $rules;
    }

    protected function agendaItem(): AgendaItem
    {
        return $this->route('agendaItem');
    }

    private function rulesFor(ApplicationFormRow $row): array
    {
        $presence = $row->required ? 'required' : 'nullable';
        $options = Rule::in($row->applicationFormRowOptions->pluck('value')->all());

        return match ($row->type) {
            ApplicationFormRow::FORM_TYPE_NUMBER => [$row->id => [$presence, 'numeric']],
            ApplicationFormRow::FORM_TYPE_CHECK_BOX => [$row->id => $row->required ? ['accepted'] : ['nullable', Rule::in(['on', '0'])]],
            ApplicationFormRow::FORM_TYPE_SELECT => [$row->id => [$presence, $options]],
            // Radio buttons and checkboxes are posted as arrays.
            ApplicationFormRow::FORM_TYPE_RADIO => [$row->id => [$presence, 'array', 'max:1'], $row->id.'.*' => [$options]],
            ApplicationFormRow::FORM_TYPE_MULTI_CHECK_BOX => [$row->id => [$presence, 'array'], $row->id.'.*' => [$options]],
            default => [$row->id => [$presence, 'string']],
        };
    }
}
