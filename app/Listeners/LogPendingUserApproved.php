<?php

namespace App\Listeners;

use App\Events\PendingUserApproved;
use App\Repositories\UserEventLogEntryRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use ReflectionClass;

class LogPendingUserApproved implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @param UserEventLogEntryRepository $logEntryRepository
     */
    public function __construct(private readonly UserEventLogEntryRepository $logEntryRepository)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(PendingUserApproved $event): void
    {
        $this->logEntryRepository->create([
            'event_type' => new ReflectionClass($event)->getShortName(),
            'event_details' => $event->user->getName() . " was approved as a member",
            'user_id' => $event->user->id,
        ]);
    }
}
