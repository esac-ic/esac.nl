<?php

namespace App\Jobs;

use App\CustomClasses\MailList\MailListFacade;
use App\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveUserFromCurrentMemberMaillists implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public User $user
    )
    {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MailListFacade $mailListFacade)
    {
        //check member type and fetch the maillists
        switch ($this->user->kind_of_member) {
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
        
        $mailListFacade->removeUserFromSpecifiedMailLists($this->user->email, $mailLists);
    }
}
