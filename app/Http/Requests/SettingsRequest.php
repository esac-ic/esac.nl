<?php

namespace App\Http\Requests;

use App\Enums\MemberType;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function rules(): array
    {
        $memberTypes = collect(MemberType::cases())->pluck('value')->implode(',');

        return [
            'killswitch' => ['required', 'boolean'],
            'footer_phone_number' => ['nullable', 'string', 'max:255'],
            'blocked_email_domains' => ['array'],
            'blocked_email_domains.*' => ['string', 'max:255'],
            'mail_lists' => ['required', 'array:'.$memberTypes, 'required_array_keys:'.$memberTypes],
            'mail_lists.*' => ['array'],
            'mail_lists.*.*' => ['string', 'max:255'],
        ];
    }

    /**
     * The lists are entered one item per line.
     */
    protected function prepareForValidation(): void
    {
        $lines = fn (?string $text) => str($text)->explode("\n")->map(fn (string $line) => trim($line))->filter()->values()->all();

        $this->merge([
            'blocked_email_domains' => $lines($this->input('blocked_email_domains')),
            'mail_lists' => array_map($lines, (array) $this->input('mail_lists')),
        ]);
    }
}
