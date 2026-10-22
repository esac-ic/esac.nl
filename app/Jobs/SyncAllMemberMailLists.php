<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Adds every current member to the mail lists of their member type.
 */
class SyncAllMemberMailLists implements ShouldQueue
{
    use Queueable;

    public function handle(Mailman $mailman): void
    {
        Log::info('Performing a mass mail list sync');

        foreach (User::query()->active()->get() as $user) {
            if ($mailLists = $user->member_type->mailLists()) {
                $mailman->addToMailLists($user->email, $user->name, $mailLists);
            }
        }

        Log::info('Mass mail list sync finished');
    }
}
