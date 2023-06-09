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
        $activeUsers = $this->userRepository->getCurrentUsers(['*'],['certificates']);
        $exportData = [];
        foreach ($activeUsers as $user){
            $user->makeHidden(['updated_at', 'lid_af', 'pending_user']);
            $data = $user->toArray();
            $data['certificates'] = $user->getCertificationsAbbreviations();
            array_push($exportData,$data);
        }

        return new Collection($exportData);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return trans('user.active_members');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            "#",
            trans('user.email'),
            trans('user.firstname'),
            trans('user.preposition'),
            trans('user.lastname'),
            trans('user.street'),
            trans('user.housenumber'),
            trans('user.city'),
            trans('user.zipcode'),
            trans('user.country'),
            trans('user.phonenumber'),
            trans('user.phonenumber_alt'),
            trans('user.emNumber'),
            trans('user.emHouseNumber'),
            trans('user.emstreet'),
            trans('user.emcity'),
            trans('user.emzipcode'),
            trans('user.emcountry'),
            trans('user.birthDay'),
            trans('user.gender'),
            trans('user.kind_of_member'),
            trans('user.IBAN'),
            trans('user.BIC'),
            trans('user.incasso'),
            trans('user.remark'),
            trans('user.created_at'),
            trans('user.certificates'),
        ];
    }
}
