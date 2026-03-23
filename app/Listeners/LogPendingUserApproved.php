<?php

namespace App\Listeners;

use App\Events\PendingUserApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserEventLogEntry;
use App\Enums\UserEventTypes;
use ReflectionClass;

class LogPendingUserApproved implements ShouldQueue
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
    public function handle(PendingUserApproved $event): void
    {
        $logEntry = new UserEventLogEntry();
        $logEntry->user()->associate($event->user);
        $logEntry->eventType = (new ReflectionClass($event))->getShortName();
        $logEntry->eventDetails = $event->user->getName() . " was approved as a member";
        $logEntry->save();
    }
}
