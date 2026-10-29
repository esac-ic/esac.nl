<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Adds a member to the mail lists of their member type. The mail list sync queues one per member, so each is retried
 * on its own.
 */
class AddMemberToMailLists implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user) {}

    public function handle(Mailman $mailman): void
    {
        $mailLists = $this->user->member_type?->mailLists() ?? [];

        if ($mailLists !== []) {
            $mailman->addToMailLists($this->user->email, $this->user->name, $mailLists);
        }
    }
}
