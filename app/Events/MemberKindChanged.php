<?php

namespace App\Events;

use App\Enums\KindOfMember;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberKindChanged implements LoggableUserEventInterface
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public KindOfMember $previousMemberType, public KindOfMember $newMemberType) {}

    public function logDetails(): string
    {
        return $this->user->getName().' changed from '.$this->previousMemberType->value.' to '.$this->newMemberType->value;
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
