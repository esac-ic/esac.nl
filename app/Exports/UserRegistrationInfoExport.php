<?php

namespace App\Exports;

use App\Models\User\UserRegistrationInfo;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserRegistrationInfoExport implements FromCollection, WithTitle, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $userRegistrations = UserRegistrationInfo::query()
            ->with('user')
            ->whereDate('created_at','>', Carbon::now()->subMonth(3))
            ->get();

        $data = [];
        foreach ($userRegistrations as $registration) {
            $user = $registration->user;
            $data[] = [
                'name' => $user->getName(),
                'email' => $user->email,
                'phone' => $user->phonenumber,
                'introPackage' => $registration->intro_package ? trans('menu.yes') : trans('menu.no'),
                'topropCourse' => $registration->toprope_course ? trans('menu.yes') : trans('menu.no'),
                'shirt' => trans('user.shirtSizes')[$registration->shirt_size],
                'weekendDate' => $registration->intro_weekend_date != "" ? $registration->intro_weekend_date->format('d-m-Y') : ""
            ];
        }

        return new Collection($data);
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return trans('user.registrationInfo');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            trans('user.name'),
            trans('user.email'),
            trans('user.phonenumber'),
            trans('user.introPackage'),
            trans('user.topropCourse'),
            trans('user.tshirt'),
            trans('user.introWeekend'),
        ];
    }
}
