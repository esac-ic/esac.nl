<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;

class OldUsersExport extends SpreadsheetExport
{
    public function collection(): Collection
    {
        return User::query()->old()->get(['id', 'firstname', 'preposition', 'lastname', 'created_at', 'lid_af']);
    }

    /**
     * @param  User  $user
     */
    public function map(mixed $user): array
    {
        return [$user->id, $user->firstname, $user->preposition, $user->lastname, $user->created_at, $user->lid_af->toDateString()];
    }

    public function title(): string
    {
        return 'Old members';
    }

    public function headings(): array
    {
        return [
            '#',
            'First name',
            'Preposition',
            'Last name',
            'User created at',
            'User removed at',
        ];
    }
}
