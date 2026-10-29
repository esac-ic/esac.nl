<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberEmailChanged
{
    use Dispatchable, SerializesModels;

    public string $email;

    public function __construct(public User $user, public string $previousEmail)
    {
        $this->email = $user->email;
    }
}
