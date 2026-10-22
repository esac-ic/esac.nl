<?php

namespace App\Exports;

use App\Models\UserEventLogEntry;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class UserEventLogExport extends SpreadsheetExport
{
    /**
     * All properties to be included in the export. Maps model property to header label.
     * Note: the keys should be the same as the model attributes
     */
    private const array PROPERTY_LABELS = [
        'Time',
        'Event Type',
        'User name',
        'Event Details',
    ];

    public function __construct(
        private readonly ?array $eventTypes,
        private readonly Carbon $startDate,
        private readonly Carbon $endDate,
    ) {}

    public function collection(): Collection
    {
        return UserEventLogEntry::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($this->eventTypes, fn ($query) => $query->whereIn('event_type', $this->eventTypes))
            ->get()
            ->makeHidden(['updated_at']);
    }

    public function title(): string
    {
        return 'Event Log ('.now(config('app.display_timezone'))->format('d-m-Y_H:i').')';
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
        return UserEventLogExport::PROPERTY_LABELS;
    }

    public function map(mixed $event): array
    {
        return [
            $event->created_at,
            $event->event_type,
            $event->user?->getName() ?? 'User removed',
            $event->event_details,
        ];
    }
}
