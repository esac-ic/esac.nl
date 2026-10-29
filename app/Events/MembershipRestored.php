<?php

namespace App\Events;

use App\Enums\MemberLogType;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipRestored implements LogsToMemberLog
{
    use Dispatchable, SerializesModels;

    public string $email;

    public function __construct(public User $user)
    {
        $this->email = $user->email;
    }

    public function logType(): MemberLogType
    {
        return MemberLogType::MembershipRestored;
    }

    public function logDetails(): string
    {
        return $this->user->name.' became a member again';
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
