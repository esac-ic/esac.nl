<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PennoMemberExport extends SpreadsheetExport
{
    private Carbon $since;

    public function __construct(int $daysAgo)
    {
        $this->since = today(config('app.display_timezone'))->subDays($daysAgo);
    }

    public function collection(): Collection
    {
        return User::query()->active()->where('created_at', '>=', $this->since->copy()->utc())->get();
    }

    /**
     * @param  User  $user
     */
    public function map(mixed $user): array
    {
        return [
            $user->id,
            $user->email,
            $user->first_name,
            $user->preposition,
            $user->last_name,
            $user->city,
            $user->country,
            $user->member_type?->value,
            str_replace(' ', '', (string) $user->iban),
            $user->bic,
            $user->direct_debit ? 'Yes' : 'No',
            $user->created_at->tz(config('app.display_timezone'))->toDateString(),
        ];
    }

    public function title(): string
    {
        return 'New members';
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
            'Member type',
            'IBAN',
            'BIC',
            'Direct debit',
            'Member since',
        ];
    }

    public function since(): Carbon
    {
        return $this->since;
    }
}
