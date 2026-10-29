<?php

namespace App\Events;

use App\Enums\MemberLogType;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipEnded implements LogsToMemberLog
{
    use Dispatchable, SerializesModels;

    public string $email;

    public function __construct(public User $user)
    {
        $this->email = $user->email;
    }

    public function logType(): MemberLogType
    {
        return MemberLogType::MembershipEnded;
    }

    public function logDetails(): string
    {
        return $this->user->name.' became a former member';
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
