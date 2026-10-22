<?php

namespace App\Events;

use App\Models\User;

/**
 * Interface for all events that add to the user event log.
 */
interface LoggableUserEventInterface
{
    /**
     * The description of the event in the log.
     */
    public function logDetails(): string;

    /**
     * The user the log entry belongs to, or null when the user is gone.
     */
    public function loggedUser(): ?User;
}
