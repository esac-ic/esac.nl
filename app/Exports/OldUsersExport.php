<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;

class OldUsersExport extends SpreadsheetExport
{
    public function collection(): Collection
    {
        $oldUsers = User::old()->get(['id', 'firstname', 'preposition', 'lastname', 'created_at', 'lid_af']);

        return $oldUsers->map(fn ($user) => $user->toArray());
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
