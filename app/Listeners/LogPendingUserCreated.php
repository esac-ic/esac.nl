<?php

namespace App\Listeners;

use App\Events\PendingUserCreated;
use App\Repositories\UserEventLogEntryRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use ReflectionClass;

class LogPendingUserCreated implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @param UserEventLogEntryRepository $logEntryRepository
     */
    public function __construct(private readonly UserEventLogEntryRepository $logEntryRepository)
    {
    }
    
    /**
     * Handle the event.
     */
    public function handle(PendingUserCreated $event): void
    {
        $this->logEntryRepository->create([
            'event_type' => new ReflectionClass($event)->getShortName(),
            'event_details' => "New pending user: " . $event->user->getName(),
            'user_id' => $event->user->id,
        ]);
    }
}
