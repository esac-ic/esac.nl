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
        return User::query()->current()->where('created_at', '>=', $this->fromDate->copy()->utc())->get();
    }

    /**
     * @param  User  $user
     */
    public function map(mixed $user): array
    {
        return [
            $user->id,
            $user->email,
            $user->firstname,
            $user->preposition,
            $user->lastname,
            $user->city,
            $user->country,
            $user->kind_of_member?->value,
            str_replace(' ', '', (string) $user->iban),
            $user->bic,
            $user->direct_debit ? 'Yes' : 'No',
            $user->created_at->tz(config('app.display_timezone'))->toDateString(),
        ];
    }

    public function title(): string
    {
        return 'Non administrated members';
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
