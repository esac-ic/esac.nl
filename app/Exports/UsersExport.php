<?php

namespace App\Exports;

use App\Repositories\UserRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UsersExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
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
        $activeUsers = $this->userRepository->getCurrentUsers(['*'], ['certificates']);
        $exportData = [];
        foreach ($activeUsers as $user) {
            $user->makeHidden(['updated_at', 'lid_af', 'pending_user']);
            $data = $user->toArray();
            $data['certificates'] = $user->getCertificationsAbbreviations();
            array_push($exportData, $data);
        }

        return new Collection($exportData);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Active members';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            "#",
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
            'Birthdate',
            'Kind of member',
            'IBAN',
            'BIC',
            'Accept Automatic Collection',
            'Remarks',
            'User created at',
            'Certificates',
        ];
    }
}
