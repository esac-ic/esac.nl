<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberBecameOldMember implements LoggableUserEventInterface
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    public function logDetails(): string
    {
        return $this->user->getName() . " became an old member";
    }

    public function loggedUser(): ?User
    {
        return $this->user;
    }
}
