<?php

namespace App\Listeners;

use App\Events\LogsToMemberLog;
use App\Models\MemberLogEntry;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class WriteMemberLogEntry implements ShouldQueueAfterCommit
{
    public function handle(LogsToMemberLog $event): void
    {
        $logEntry = new MemberLogEntry([
            'type' => $event->logType(),
            'details' => $event->logDetails(),
        ]);
        $logEntry->user()->associate($event->loggedUser());
        $logEntry->save();
    }
}
