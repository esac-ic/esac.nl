<?php

namespace App\Listeners;

use App\Events\LoggableUserEventInterface;
use App\Models\UserEventLogEntry;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class LogUserEvent implements ShouldQueueAfterCommit
{
    /**
     * Add the event to the user event log.
     */
    public function handle(LoggableUserEventInterface $event): void
    {
        $logEntry = new UserEventLogEntry([
            'event_type' => class_basename($event),
            'event_details' => $event->logDetails(),
        ]);
        $logEntry->user()->associate($event->loggedUser());
        $logEntry->save();
    }
}
