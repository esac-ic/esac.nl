<?php

namespace App\Events;

use App\Enums\MemberLogType;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class SignUpRejected implements LogsToMemberLog
{
    use Dispatchable;

    public function __construct(public string $memberName) {}

    public function logType(): MemberLogType
    {
        return MemberLogType::SignUpRejected;
    }

    public function logDetails(): string
    {
        return $this->memberName.' was removed as a pending member';
    }

    public function loggedUser(): ?User
    {
        return null;
    }
}
