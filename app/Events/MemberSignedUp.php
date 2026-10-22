<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberSignedUp implements LogsToMemberLog
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user) {}

    public function logDetails(): string
    {
        return 'New pending member: '.$this->user->name;
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
