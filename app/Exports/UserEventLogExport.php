<?php

namespace App\Exports;

use App\Repositories\UserEventLogEntryRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class UserEventLogExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithMapping
{
    private UserEventLogEntryRepository $repository;
    private Array | null $names;
    private Array | null $eventTypes;
    private Carbon $startDate;
    private Carbon $endDate;
    
     /**
     * All properties to be included in the export. Maps model property to header label.
     * Note: the keys should be the same as the model attributes
     */
    private const PROPERTY_LABELS = [
        "Time",
        "Event Type",
        "User name",
        "Event Details",
    ];
    
    public function __construct(UserEventLogEntryRepository $userEventLogEntryRepository, Array | null $eventTypes, Array | null $names, Carbon $startDate, Carbon $endDate)
    {
        $this->repository = $userEventLogEntryRepository;
        $this->eventTypes = $eventTypes;
        $this->names = $names;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    
    public function collection()
    {
        $collection = $this->repository->findLogs($this->eventTypes, $this->startDate, $this->endDate);
        return $collection;
    }
    
    public function title(): String
    {
        //add one hour because at time of writing project timezone is set to UTC instead of UTC+1
        return 'Event Log (' . Carbon::now()->addHour()->format("d-m-Y_H:i") . ')';
    }
    
    /**
     * Returns the title but in a format that is nicer for filenames.
     * 
     * @return string
     */
    public function fileName(): string
    {
        return str_replace(':', '', $this->title());
    }
    
    public function headings(): array
    {
        return array_values(UserEventLogExport::PROPERTY_LABELS);
    }
    
    public function map($event): array
    {
        return [
            $event->created_at,
            $event->event_type,
            $event->user?->getName() ?? "User removed",
            $event->event_details,
        ];
    }
}