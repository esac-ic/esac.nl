<?php

namespace App\Events;

use App\Models\User;

/**
 * The events that add an entry to the member log.
 */
interface LogsToMemberLog
{
    /**
     * The details of the entry.
     */
    public function logDetails(): string;

    /**
     * The member the entry belongs to, or null when the member is gone.
     */
    public function loggedUser(): ?User;
}
