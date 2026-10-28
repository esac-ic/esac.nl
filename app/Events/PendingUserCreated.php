<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendingUserCreated implements LoggableUserEventInterface
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user) {}

    public function logDetails(): string
    {
        return 'New pending user: '.$this->user->getName();
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
