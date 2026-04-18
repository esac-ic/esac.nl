<?php

namespace App\Listeners;

use App\Events\OldMemberBecameMember;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserEventLogEntry;
use App\Enums\UserEventTypes;
use ReflectionClass;

class LogOldMemberBecameMember implements ShouldQueue
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
    public function handle(OldMemberBecameMember $event): void
    {
        $logEntry = new UserEventLogEntry();
        $logEntry->user()->associate($event->user);
        $logEntry->event_type = (new ReflectionClass($event))->getShortName();
        $logEntry->event_details = $event->user->getName() . " became a member again";
        $logEntry->save();
    }
}
