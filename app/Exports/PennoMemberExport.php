<?php

namespace App\Exports;

use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PennoMemberExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = $this->userRepository->getTreasurerPendingUsers($this->columns());

        return $users->map(function (User $user) {
            $export = $user->toArray();
            $export['IBAN'] = str_replace(' ', '', $export['IBAN']);
            // Remove the time component of the created_at field:
            $export['created_at'] = $user->created_at->toDateString();
            $export['incasso'] = $user->incasso ? "Yes" : "No";
            return $export;
        });
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Non administrated members';
    }

    /**
     * @return array
     */
    private function columns(): array
    {
        return [
            "id",
            'email',
            'firstname',
            'preposition',
            'lastname',
            'city',
            'country',
            'kind_of_member',
            'IBAN',
            'BIC',
            'incasso',
            'created_at',
            'treasurer_pending',
        ];
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
            'City',
            'Country',
            'Kind of member',
            'IBAN',
            'BIC',
            'Accept Automatic Collection',
            'User created at',
            'Neccesary approval (which fields changed/new member)',
        ];
    }
}