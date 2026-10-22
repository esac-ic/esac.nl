<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Adds every current member to the mail lists of their kind of member.
 */
class MemberMassMailListSyncJob implements ShouldQueue
{
    use Queueable;

    public function handle(Mailman $mailman): void
    {
        Log::info('Performing a mass mail list sync');

        foreach (User::query()->current()->get() as $user) {
            if ($mailLists = $user->kind_of_member->mailLists()) {
                $mailman->addUserToSpecifiedMailLists($user->email, $user->getName(), $mailLists);
            }
        }

        Log::info('Mass mail list sync finished');
    }
}
