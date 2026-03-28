<?php

namespace App\Exports;

use App\Repositories\UserRepository;
use DateInterval;
use DateTime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PennoExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    private $userRepository;
    private $fromDay;

    public function __construct(UserRepository $userRepository, int $daysAgo)
    {
        $this->userRepository = $userRepository;
        // Transform daysAgo to a mysql timestamp format:
        $now = (new DateTime('NOW'/*, new DateTimeZone('Europe/Amsterdam')*/))->setTime(0,0,0);
        $this->fromDay = $now->sub(DateInterval::createFromDateString("$daysAgo day"))->format('Y-m-d H:i:s');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = $this->userRepository->getCurrentUsersAfterSignupDate($this->columns(), $this->fromDay);

        return $users->map(function ($user) {
            $export = $user->toArray();
            $export['IBAN'] = str_replace(' ', '', $export['IBAN']);
            // Remove the time component of the created_at field:
            $export['created_at'] = str_replace(' ', '', strtok($export['created_at'], 'T'));
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
            'created_at'
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
            'User created at'
        ];
    }
}