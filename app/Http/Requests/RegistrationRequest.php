<?php

namespace App\Http\Requests;

use App\Enums\FormRowType;
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
        $rows = $this->agendaItem()->applicationForm?->applicationFormRows()->with('applicationFormRowOptions')->get() ?? [];

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
            FormRowType::Number => [$row->id => [$presence, 'numeric']],
            FormRowType::CheckBox => [$row->id => $row->required ? ['accepted'] : ['nullable', Rule::in(['on', '0'])]],
            FormRowType::Select => [$row->id => [$presence, $options]],
            // Radio buttons and checkboxes are posted as arrays.
            FormRowType::Radio => [$row->id => [$presence, 'array', 'max:1'], $row->id.'.*' => [$options]],
            FormRowType::MultiCheckBox => [$row->id => [$presence, 'array'], $row->id.'.*' => [$options]],
            default => [$row->id => [$presence, 'string']],
        };
    }
}
