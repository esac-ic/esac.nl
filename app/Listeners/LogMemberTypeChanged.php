<?php

namespace App\Listeners;

use App\Enums\UserEventTypes;
use App\Events\MemberKindChanged;
use App\Models\UserEventLogEntry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

class LogMemberTypeChanged implements ShouldQueue
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
    public function handle(MemberKindChanged $event): void
    {
        $logEntry = new UserEventLogEntry();
        $logEntry->user()->associate($event->user);
        $logEntry->event_type = (new ReflectionClass($event))->getShortName();
        $logEntry->event_details = $event->user->getName() . " changed from " . $event->previousMemberType . " to " . $event->newMemberType;
        $logEntry->save();
    }
}
