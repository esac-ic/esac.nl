<?php

namespace App\Listeners;

use App\Enums\SettingKey;
use App\Events\MemberBecameOldMember;
use App\Events\MemberKindChanged;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use App\Models\Setting;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdateMemberTypeMaillists implements ShouldQueue
{
    public function __construct(private readonly Mailman $mailman) {}

    /**
     * Moves the member from the mail lists of their old kind of member to those of their new kind.
     */
    public function handleMemberKindChanged(MemberKindChanged $event): void
    {
        $oldLists = collect(self::getMemberTypeMailLists($event->previousMemberType));
        $newLists = collect(self::getMemberTypeMailLists($event->newMemberType));

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
        $this->addUserToMailLists($event->user, $event->user->kind_of_member);
    }

    public function handlePendingUserApproved(PendingUserApproved $event): void
    {
        $this->addUserToMailLists($event->user, $event->user->kind_of_member);
    }

    public function handleMemberBecameOldMember(MemberBecameOldMember $event): void
    {
        $this->mailman->deleteUserForAllMailList($event->user);
    }

    public function addUserToMailLists(User $user, string $memberType): void
    {
        $mailLists = self::getMemberTypeMailLists($memberType);

        if ($mailLists !== []) {
            $this->mailman->addUserToSpecifiedMailLists($user->email, $user->getName(), $mailLists);
        }
    }

    /**
     * The mail lists for a kind of member, from the settings.
     *
     * @return string[]
     */
    public static function getMemberTypeMailLists(string $memberType): array
    {
        $setting = SettingKey::mailListsFor($memberType);

        if ($setting === null) {
            Log::error('Tried to update maillist membership while no ESAC member type was given');

            return [];
        }

        $mailLists = trim(Setting::valueOf($setting));

        return $mailLists === '' ? [] : explode(';', $mailLists);
    }
}
