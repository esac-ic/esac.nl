<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;

class MembersExport extends SpreadsheetExport
{
    public function collection(): Collection
    {
        return User::query()->active()->with('certificates')->get();
    }

    /**
     * @param  User  $user
     */
    public function map(mixed $user): array
    {
        return [
            $user->id,
            $user->email,
            $user->first_name,
            $user->preposition,
            $user->last_name,
            $user->street,
            $user->house_number,
            $user->city,
            $user->zipcode,
            $user->country,
            $user->phone_number,
            $user->phone_number_alt,
            $user->emergency_number,
            $user->emergency_house_number,
            $user->emergency_street,
            $user->emergency_city,
            $user->emergency_zipcode,
            $user->emergency_country,
            $user->birthday?->toDateString(),
            $user->member_type?->label(),
            $user->iban,
            $user->bic,
            $user->direct_debit,
            $user->remark,
            $user->created_at,
            $user->certificate_abbreviations,
        ];
    }

    public function title(): string
    {
        return 'Active members';
    }

    public function headings(): array
    {
        return [
            '#',
            'Email address',
            'First name',
            'Preposition',
            'Last name',
            'Street',
            'House number',
            'City',
            'Postal code',
            'Country',
            'Phone number',
            'Alternative phone number',
            'Emergency phone number',
            'Emergency house number',
            'Emergency street',
            'Emergency city',
            'Emergency postal code',
            'Emergency country',
            'Date of birth',
            'Member type',
            'IBAN',
            'BIC',
            'Direct debit',
            'Remarks',
            'Signed up on',
            'Certificates',
        ];
    }
}
