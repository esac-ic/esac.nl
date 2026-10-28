<?php

namespace App\Jobs;

use App\CustomClasses\MailList\MailListFacade;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Adds one member to the mail lists of their kind of member.
 */
class MemberMassMailListSyncJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user
    )
    {
    }

    public function handle(MailListFacade $mailListFacade): void
    {
        $mailLists = UpdateMemberTypeMaillists::getMemberTypeMailLists((string) $this->user->kind_of_member);

        if ($mailLists) {
            $mailListFacade->addUserToSpecifiedMailLists($this->user->email, $this->user->getName(), $mailLists);
        }
    }
}
