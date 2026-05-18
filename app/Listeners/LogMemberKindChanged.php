<?php

namespace App\Listeners;

use App\Events\MemberKindChanged;
use App\Repositories\UserEventLogEntryRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use ReflectionClass;

class LogMemberKindChanged implements ShouldQueue
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
    public function handle(MemberKindChanged $event): void
    {
        $this->logEntryRepository->create([
            'event_type' => (new ReflectionClass($event))->getShortName(),
            'event_details' => $event->user->getName() . " changed from " . $event->previousMemberType . " to " . $event->newMemberType,
            'user_id' => $event->user->id,
        ]);
    }
}
