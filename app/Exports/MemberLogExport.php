<?php

namespace App\Exports;

use App\Models\MemberLogEntry;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MemberLogExport extends SpreadsheetExport
{
    public function __construct(
        private readonly ?array $types,
        private readonly Carbon $startDate,
        private readonly Carbon $endDate,
    ) {}

    public function collection(): Collection
    {
        return MemberLogEntry::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($this->types, fn ($query) => $query->whereIn('type', $this->types))
            ->with('user')
            ->get();
    }

    public function title(): string
    {
        return 'Member log ('.now(config('app.display_timezone'))->format('d-m-Y_H:i').')';
    }

    /**
     * Returns the title but in a format that is nicer for filenames.
     */
    public function fileName(): string
    {
        return str_replace(':', '', $this->title());
    }

    public function headings(): array
    {
        return ['Time', 'Type', 'Member', 'Details'];
    }

    /**
     * @param  MemberLogEntry  $entry
     */
    public function map(mixed $entry): array
    {
        return [
            $entry->created_at,
            $entry->type->label(),
            $entry->user->name ?? 'Account deleted',
            $entry->details,
        ];
    }
}
