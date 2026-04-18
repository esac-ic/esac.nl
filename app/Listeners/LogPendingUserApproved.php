<?php

namespace App\Listeners;

use App\Events\LoggableUserEventInterface;
use App\Events\PendingUserApproved;
use App\Repositories\UserEventLogEntryRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserEventLogEntry;
use App\Enums\UserEventTypes;
use ReflectionClass;

class LogPendingUserApproved implements ShouldQueue
{
    private UserEventLogEntryRepository $logEntryRepository;
    
    /**
     * Create the event listener.
     */
    public function __construct(UserEventLogEntryRepository $logEntryRepository)
    {
        $this->logEntryRepository = $logEntryRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(PendingUserApproved $event): void
    {
        $this->logEntryRepository->create([
            'event_type' => (new ReflectionClass($event))->getShortName(),
            'event_details' => $event->user->getName() . " was approved as a member",
            'user_id' => $event->user,
        ]);
//        $logEntry = new UserEventLogEntry();
//        $logEntry->user()->associate($event->user);
//        $logEntry->event_type = (new ReflectionClass($event))->getShortName();
//        $logEntry->event_details = $event->user->getName() . " was approved as a member";
//        $logEntry->save();
    }
}
