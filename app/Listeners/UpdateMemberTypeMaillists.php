<?php

namespace App\Listeners;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberKindChanged;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateMemberTypeMaillists implements ShouldQueue
{
    private MailListFacade $mailListFacade;
    
    /**
     * Create the event listener.
     */
    public function __construct(MailListFacade $mailListFacade)
    {
        $this->mailListFacade = $mailListFacade;
    }
    
    /**
     * Removes the member from the maillists associated with their old member type
     * and then adds them to the maillists for their new member type.
     *
     * @param MemberKindChanged $event
     * @return void
     */
    public function handleMemberKindChanged(MemberKindChanged $event): void
    {
        //compute difference in the maillists
        $oldLists = collect(self::getMemberTypeMailLists($event->previousMemberType));
        $newLists = collect(self::getMemberTypeMailLists($event->newMemberType));
        
        $removeFromLists = $oldLists->diff($newLists);
        $addToLists = $newLists->diff($oldLists);
        
        if ($removeFromLists->isNotEmpty()) {
            $this->mailListFacade->removeUserFromSpecifiedMailLists($event->user->email, $removeFromLists->all());
        }
        if ($addToLists->isNotEmpty()) {
            $this->mailListFacade->addUserToSpecifiedMailLists($event->user->email, $event->user->getName(), $addToLists->all());
        }
    }
    
    /**
     * Add the member to their membership type mail lists.
     *
     * @param OldMemberBecameMember $event
     * @return void
     */
    public function handleOldMemberBecameMember(OldMemberBecameMember $event): void
    {
        $this->addUserToMailLists($event->user, $event->user->kind_of_member);
    }
    
    
    /**
     * Adds a pending user to their member type maillists
     * (In practice this would always be the normal member maillists)
     *
     * @param PendingUserApproved $event
     * @return void
     */
    public function handlePendingUserApproved(PendingUserApproved $event): void
    {
        $this->addUserToMailLists($event->user, $event->user->kind_of_member);
    }
    
    public function subscribe(Dispatcher $events): array
    {
        return [
            OldMemberBecameMember::class => 'handleOldMemberBecameMember',
            MemberKindChanged::class => 'handleMemberKindChanged',
            PendingUserApproved::class => 'handlePendingUserApproved',
        ];
    }
    
    /**
     * Add user to the maillists for their kind of member specified in the settings table
     * @param \App\User $user
     * @param string $memberType
     * @return void
     */
    public function addUserToMailLists(\App\User $user, string $memberType): void
    {
        $mailLists = collect(self::getMemberTypeMailLists($memberType));
        
        if ($mailLists->isNotEmpty()) {
            $this->mailListFacade->addUserToSpecifiedMailLists($user->email, $user->getName(), $mailLists);
        }
    }
    
    /**
     * Fetch and process the maillists associated with a membership type in the settings.
     *
     * @param string $memberType
     * @return string[]|string
     */
    public static function getMemberTypeMailLists(string $memberType): array|string
    {
        //check member type and fetch the maillists
        switch ($memberType) {
            case \Lang::get("member"):
                $mailLists = trim(app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_NORMAL_MEMBER_MAIL_LISTS));
                break;
            case \Lang::get("extraordinary_member"):
                $mailLists = trim(app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS));
                break;
            case \Lang::get("reunist"):
                $mailLists = trim(app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_REUNIST_MEMBER_MAIL_LISTS));
                break;
            case \Lang::get("honorary_member"):
                $mailLists = trim(app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_HONORARY_MEMBER_MAIL_LISTS));
                break;
            case \Lang::get("member_of_merit"):
                $mailLists = trim(app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_MERIT_MEMBER_MAIL_LISTS));
                break;
            case \Lang::get("trainer"):
                $mailLists = trim(app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_TRAINER_MEMBER_MAIL_LISTS));
                break;
            case \Lang::get("relationship"):
                $mailLists = trim(app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS));
                break;
            default:
                //in case something goes wrong don't add the member to any maillists
                \Log::error("Tried to update maillist membership while no ESAC member type was given");
                $mailLists = "";
                break;
        }
        
        //check if there are maillists specified
        if ($mailLists == "") {
            return $mailLists;
        }
        
        //split the list of maillists
        return explode(";", $mailLists);
    }
}
