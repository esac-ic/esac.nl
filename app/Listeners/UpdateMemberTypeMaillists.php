<?php

namespace App\Listeners;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberTypeChanged;
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
     * Add user to the maillists for their kind of member specified in the settings table
     * @param \App\User $user
     * @param string $memberType
     * @return void
     */
    private function addUserToMailLists(\App\User $user, string $memberType)
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
                \Log::error("Tried to add member to mailists while no member type was given");
                $mailLists = "";
                break;
            }
                
        //check if themaillist string isn't empty
        if ($mailLists == "") {
            return;
        }
        
        //split the list of maillists
        $mailLists = explode(";", $mailLists);
                
        /*
        another option besides transforming the setting here is to make the settings follow the maillist id format
        */
        
        //transform the maillist strings to the correct format
        foreach ($mailLists as &$mailList) {
            $mailList = str_replace("@", ".", $mailList . env("MAIL_MAN_DOMAIN")); //change the @ to a . to fit the maillist id format
        }
        unset($mailList);//break the reference after the last element
        
        $this->mailListFacade->addUserToSpecifiedMailLists($user->email, $user->getName(), $mailLists);
    }
    
    /**
     * Remove user from the maillists for their kind of member specified in the settings table
     * @param \App\User $user
     * @param string $memberType
     * @return void
     */
    private function removeUserFromMailLists(\App\User $user, string $memberType)
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
                \Log::error("Tried to add member to mailists while no member type was given");
                $mailLists = "";
                break;
        }
            
        //check if there are mailists specified
        if ($mailLists == "") {
            return;
        }
        
        //split the list of maillists
        $mailLists = explode(";", $mailLists);
                
        //another option besides transforming the setting here is to make the settings follow the maillist id format
        
        //transform the maillist strings to the correct format
        foreach ($mailLists as &$mailList) {
            $mailList = str_replace("@", ".", $mailList . env("MAIL_MAN_DOMAIN")); //change the @ to a . to fit the maillist id format
        }
        unset($mailList);//break the reference after the last element
        
        $this->mailListFacade->removeUserFromSpecifiedMailLists($user->email, $mailLists);
    }
    
    public function handleOldMemberBecameMember(OldMemberBecameMember $event)
    {
        $this->addUserToMailLists($event->user, $event->user->kind_of_member);
    }
    
    public function handleMemberTypeChanged(MemberTypeChanged $event)
    {
        $this->removeUserFromMailLists($event->user, $event->oldMemberType);
        $this->addUserToMailLists($event->user, $event->newMemberType);
    }
    
    public function handlePendingUserApproved(PendingUserApproved $event)
    {
        $this->addUserToMailLists($event->user, $event->user->kind_of_member);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            OldMemberBecameMember::class => 'handleOldMemberBecameMember',
            MemberTypeChanged::class => 'handleMemberTypeChanged',
            PendingUserApproved::class => 'handlePendingUserApproved',
        ];
    }
}
