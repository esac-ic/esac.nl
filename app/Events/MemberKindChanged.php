<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberKindChanged implements LoggableUserEventInterface
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public string $previousMemberType, public string $newMemberType) {}

    public function logDetails(): string
    {
        return $this->user->getName().' changed from '.$this->previousMemberType.' to '.$this->newMemberType;
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
