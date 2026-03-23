<?php

namespace App\Listeners;

use App\Events\PendingUserRemoved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserEventLogEntry;
use App\Enums\UserEventTypes;
use ReflectionClass;

class LogPendingUserRemoved implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PendingUserRemoved $event): void
    {
        $logEntry = new UserEventLogEntry();
        $logEntry->user()->associate(null);
        $logEntry->eventType = (new ReflectionClass($event))->getShortName();
        $logEntry->eventDetails = $event->userName . " was removed as a pending member";
        $logEntry->save();
    }
}
