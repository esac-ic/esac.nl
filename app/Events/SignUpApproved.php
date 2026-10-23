<?php

namespace App\Events;

use App\Enums\MemberLogType;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SignUpApproved implements LogsToMemberLog
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user) {}

    public function logType(): MemberLogType
    {
        return MemberLogType::SignUpApproved;
    }

    public function logDetails(): string
    {
        return $this->user->name.' was approved as a member';
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
