<?php

namespace App\Exports;

use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

class OldUsersExport extends SpreadsheetExport
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $oldUsers = $this->userRepository->getOldUsers(['id', 'firstname', 'preposition', 'lastname', 'created_at', 'lid_af']);

        return $oldUsers->map(fn ($user) => $user->toArray());
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Old members';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            "#",
            'First name',
            'Preposition',
            'Last name',
            'User created at',
            'User removed at',
        ];
    }
}
