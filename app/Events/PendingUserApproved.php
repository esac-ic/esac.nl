<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendingUserApproved implements LoggableUserEventInterface
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user) {}

    public function logDetails(): string
    {
        return $this->user->getName().' was approved as a member';
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
