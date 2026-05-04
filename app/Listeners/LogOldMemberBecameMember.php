<?php

namespace App\Listeners;

use App\Events\OldMemberBecameMember;
use App\Repositories\UserEventLogEntryRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use ReflectionClass;

class LogOldMemberBecameMember implements ShouldQueue
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
    public function handle(OldMemberBecameMember $event): void
    {
        $this->logEntryRepository->create([
            'event_type' => (new ReflectionClass($event))->getShortName(),
            'event_details' => $event->user->getName() . " became a member again",
            'user_id' => $event->user->id,
        ]);
    }
}
