<?php

namespace App\Events;

use App\Enums\MemberLogType;
use App\Models\User;

interface LogsToMemberLog
{
    public function logType(): MemberLogType;

    public function logDetails(): string;

    /**
     * The member the entry belongs to, or null when the member is gone.
     */
    public function loggedUser(): ?User;
}
