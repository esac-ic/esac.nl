<?php

namespace App\Jobs;

use App\Listeners\UpdateMemberTypeMaillists;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class MemberMassMailListSyncJob implements ShouldQueue
{
    use Queueable;

    /**
     * @param  Collection<int, User>  $users
     */
    public function __construct(public Collection $users) {}

    public function handle(Mailman $mailman): void
    {
        Log::info('Performing a mass mail list sync');

        $mailListsPerMemberKind = [];
        foreach (User::KINDS_OF_MEMBER as $kind_of_member) {
            $mailListsPerMemberKind[$kind_of_member] = UpdateMemberTypeMaillists::getMemberTypeMailLists($kind_of_member);
        }

        foreach ($this->users as $user) {
            if ($mailListsPerMemberKind[$user->kind_of_member] ?? false) {
                $mailman->addUserToSpecifiedMailLists($user->email, $user->getName(), $mailListsPerMemberKind[$user->kind_of_member]);
            }
        }

        Log::info('Mass mail list sync finished');
    }
}
