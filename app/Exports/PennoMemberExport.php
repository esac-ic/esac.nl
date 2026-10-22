<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PennoMemberExport extends SpreadsheetExport
{
    private Carbon $fromDate;

    public function __construct(int $daysAgo)
    {
        $this->fromDate = today(config('app.display_timezone'))->subDays($daysAgo);
    }

    public function collection(): Collection
    {
        $users = User::query()->current()->where('created_at', '>=', $this->fromDate->copy()->utc())->get($this->columns());

        return $users->map(function (User $user) {
            $export = $user->toArray();
            $export['iban'] = str_replace(' ', '', $export['iban']);
            // Remove the time component of the created_at field:
            $export['created_at'] = $user->created_at->tz(config('app.display_timezone'))->toDateString();
            $export['incasso'] = $user->incasso ? 'Yes' : 'No';

            return $export;
        });
    }

    public function title(): string
    {
        return 'Non administrated members';
    }

    private function columns(): array
    {
        return [
            'id',
            'email',
            'firstname',
            'preposition',
            'lastname',
            'city',
            'country',
            'kind_of_member',
            'iban',
            'bic',
            'incasso',
            'created_at',
        ];
    }

    public function headings(): array
    {
        return [
            '#',
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
        ];
    }

    public function getFromDate(): Carbon
    {
        return $this->fromDate;
    }
}
