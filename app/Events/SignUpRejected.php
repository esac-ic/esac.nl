<?php

namespace App\Events;

use App\Enums\MemberLogType;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SignUpRejected implements LogsToMemberLog
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public string $memberName) {}

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
