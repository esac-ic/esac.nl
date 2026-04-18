<?php

namespace App\Listeners;

use App\Events\PendingUserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserEventLogEntry;
use App\Enums\UserEventTypes;
use ReflectionClass;

class LogNewPendingUser
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
    public function handle(PendingUserCreated $event): void
    {
        $logEntry = new UserEventLogEntry();
        $logEntry->user()->associate($event->user);
        $logEntry->event_type = (new ReflectionClass($event))->getShortName();
        $logEntry->event_details = "New pending user: " . $event->user->getName();
        $logEntry->save();
    }
}
