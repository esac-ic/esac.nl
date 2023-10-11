<?php

namespace App\Exports;

use App\Models\User\UserRegistrationInfo;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserRegistrationInfoExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $userRegistrations = UserRegistrationInfo::query()
            ->with('user')
            ->whereDate('created_at', '>', Carbon::now()->subMonth(3))
            ->get();

        $data = [];
        foreach ($userRegistrations as $registration) {
            $user = $registration->user;
            $data[] = [
                'name' => $user->getName(),
                'email' => $user->email,
                'phone' => $user->phonenumber,
                'introPackage' => trans('user.packageTypes.' . $registration->package_type),
                'shirt' => trans('user.shirtSizes.' . $registration->shirt_size),
                'weekendDate' => trans('user.weekendDates.' . $registration->intro_weekend),
            ];
        }

        return new Collection($data);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Registration info';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Email address',
            'Phone number',
            'Intro package',
            'ESAC Intro shirt',
            'Intro weekend',
        ];
    }
}
