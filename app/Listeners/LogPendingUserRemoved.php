<?php

namespace App\Listeners;

use App\Events\LoggableUserEventInterface;
use App\Events\PendingUserRemoved;
use App\Repositories\UserEventLogEntryRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserEventLogEntry;
use App\Enums\UserEventTypes;
use ReflectionClass;

class LogPendingUserRemoved implements ShouldQueue
{
    private UserEventLogEntryRepository $logEntryRepository;
    
    /**
     * Create the event listener.
     *
     * @param UserEventLogEntryRepository $logEntryRepository
     */
    public function __construct(UserEventLogEntryRepository $logEntryRepository)
    {
        $this->logEntryRepository = $logEntryRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(PendingUserRemoved $event): void
    {
        $this->logEntryRepository->create([
            'event_type' => (new ReflectionClass($event))->getShortName(),
            'event_details' => $event->userName . " was removed as a pending member",
            'user_id' => null,
        ]);
    }
}
