<?php

namespace App\Listeners;

use App\Events\MemberBecameOldMember;
use App\Events\MemberKindChanged;
use App\Repositories\UserEventLogEntryRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserEventLogEntry;
use App\Enums\UserEventTypes;
use ReflectionClass;

class LogMemberBecameOldMember implements ShouldQueue
{
    private UserEventLogEntryRepository $logEntryRepository;
    
    /**
     * Create the event listener.
     *
     * @param UserEventLogEntryRepository $logEntryRepository
     */
    public function __construct(UserEventLogEntryRepository $logEntryRepository)
    {
        $this->logEntryRepository = $logEntryRepository;
    }
    
    /**
     * Handle the event.
     */
    public function handle(MemberBecameOldMember $event): void
    {
        $this->logEntryRepository->create([
            'event_type' => (new ReflectionClass($event))->getShortName(),
            'event_details' => $event->user->getName() . " became an old member",
            'user_id' => $event->user,
        ]);
    }
}
