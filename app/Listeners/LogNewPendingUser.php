<?php

namespace App\Listeners;

use App\Events\NewPendingUser;
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
    public function handle(NewPendingUser $event): void
    {
        $logEntry = new UserEventLogEntry();
        $logEntry->user()->associate($event->user);
        $logEntry->eventType = (new ReflectionClass($event))->getShortName();
        $logEntry->eventDetails = "New pending user: " . $event->user->getName();
        $logEntry->save();
    }
}
