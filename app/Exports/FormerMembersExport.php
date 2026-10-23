<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;

class FormerMembersExport extends SpreadsheetExport
{
    public function collection(): Collection
    {
        return User::query()->former()->get(['id', 'first_name', 'preposition', 'last_name', 'created_at', 'membership_ended_on']);
    }

    /**
     * @param  User  $user
     */
    public function map(mixed $user): array
    {
        return [$user->id, $user->first_name, $user->preposition, $user->last_name, $user->created_at, $user->membership_ended_on->toDateString()];
    }

    public function title(): string
    {
        return 'Former members';
    }

    public function headings(): array
    {
        return [
            '#',
            'First name',
            'Preposition',
            'Last name',
            'Signed up on',
            'Membership ended on',
        ];
    }
}
