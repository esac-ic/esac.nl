<?php

namespace App\Listeners;

use App\Events\MemberEmailChanged;
use App\Events\MembershipEnded;
use App\Events\MembershipRestored;
use App\Events\MemberTypeChanged;
use App\Events\SignUpApproved;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class SyncMemberMailLists implements ShouldQueueAfterCommit
{
    public function __construct(private readonly Mailman $mailman) {}

    /**
     * Moves the member from the mail lists of their previous member type to those of their new one.
     */
    public function handleMemberTypeChanged(MemberTypeChanged $event): void
    {
        $previousLists = collect($event->previousMemberType->mailLists());
        $newLists = collect($event->newMemberType->mailLists());

        $removeFromLists = $previousLists->diff($newLists);
        $addToLists = $newLists->diff($previousLists);

        if ($removeFromLists->isNotEmpty()) {
            $this->mailman->removeFromMailLists($event->user->email, $removeFromLists->all());
        }
        if ($addToLists->isNotEmpty()) {
            $this->mailman->addToMailLists($event->user->email, $event->user->name, $addToLists->all());
        }
    }

    public function handleMembershipRestored(MembershipRestored $event): void
    {
        $this->addToMailLists($event->user);
    }

    public function handleSignUpApproved(SignUpApproved $event): void
    {
        $this->addToMailLists($event->user);
    }

    public function handleMemberEmailChanged(MemberEmailChanged $event): void
    {
        $this->mailman->changeEmailOnAllMailLists($event->user, $event->previousEmail, $event->user->email);
    }

    public function handleMembershipEnded(MembershipEnded $event): void
    {
        $this->mailman->removeFromAllMailLists($event->user);
    }

    private function addToMailLists(User $member): void
    {
        $mailLists = $member->member_type->mailLists();

        if ($mailLists !== []) {
            $this->mailman->addToMailLists($member->email, $member->name, $mailLists);
        }
    }
}
