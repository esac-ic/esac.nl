<?php

namespace App\Exports;

use App\Repositories\UserRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class OldUsersExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $oldUsers = $this->userRepository->getOldUsers(['id', 'firstname', 'preposition', 'lastname', 'created_at', 'lid_af']);

        return $oldUsers->map(function ($user) {
            return $user->toArray();
        });
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
