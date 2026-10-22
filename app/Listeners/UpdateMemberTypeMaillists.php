<?php

namespace App\Listeners;

use App\Events\MemberBecameOldMember;
use App\Events\MemberEmailChanged;
use App\Events\MemberKindChanged;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class UpdateMemberTypeMaillists implements ShouldQueueAfterCommit
{
    public function __construct(private readonly Mailman $mailman) {}

    /**
     * Moves the member from the mail lists of their old kind of member to those of their new kind.
     */
    public function handleMemberKindChanged(MemberKindChanged $event): void
    {
        $oldLists = collect($event->previousMemberType->mailLists());
        $newLists = collect($event->newMemberType->mailLists());

        $removeFromLists = $oldLists->diff($newLists);
        $addToLists = $newLists->diff($oldLists);

        if ($removeFromLists->isNotEmpty()) {
            $this->mailman->removeUserFromSpecifiedMailLists($event->user->email, $removeFromLists->all());
        }
        if ($addToLists->isNotEmpty()) {
            $this->mailman->addUserToSpecifiedMailLists($event->user->email, $event->user->getName(), $addToLists->all());
        }
    }

    public function handleOldMemberBecameMember(OldMemberBecameMember $event): void
    {
        $this->addUserToMailLists($event->user);
    }

    public function handlePendingUserApproved(PendingUserApproved $event): void
    {
        $this->addUserToMailLists($event->user);
    }

    public function handleMemberEmailChanged(MemberEmailChanged $event): void
    {
        $this->mailman->updateUserEmailForAllMailList($event->user, $event->previousEmail, $event->user->email);
    }

    public function handleMemberBecameOldMember(MemberBecameOldMember $event): void
    {
        $this->mailman->deleteUserForAllMailList($event->user);
    }

    private function addUserToMailLists(User $user): void
    {
        $mailLists = $user->kind_of_member->mailLists();

        if ($mailLists !== []) {
            $this->mailman->addUserToSpecifiedMailLists($user->email, $user->getName(), $mailLists);
        }
    }
}
