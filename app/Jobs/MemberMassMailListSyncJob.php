<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Adds one member to the mail lists of their kind of member. The mass sync queues one per member, so each is retried
 * on its own.
 */
class MemberMassMailListSyncJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user) {}

    public function handle(Mailman $mailman): void
    {
        $mailLists = $this->user->kind_of_member?->mailLists() ?? [];

        if ($mailLists !== []) {
            $mailman->addUserToSpecifiedMailLists($this->user->email, $this->user->getName(), $mailLists);
        }
    }
}
