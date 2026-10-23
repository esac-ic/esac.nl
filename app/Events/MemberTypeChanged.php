<?php

namespace App\Events;

use App\Enums\MemberLogType;
use App\Enums\MemberType;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberTypeChanged implements LogsToMemberLog
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public MemberType $previousMemberType, public MemberType $newMemberType) {}

    public function logType(): MemberLogType
    {
        return MemberLogType::MemberTypeChanged;
    }

    public function logDetails(): string
    {
        return $this->user->name.' changed from '.$this->previousMemberType->label().' to '.$this->newMemberType->label();
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
