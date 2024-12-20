<?php

namespace App\Jobs;

use App\CustomClasses\MailList\MailListFacade;
use App\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddUserToOldMemberMaillists implements ShouldQueue
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
        //get the value of the setting
        $oldMemberMailLists = trim(app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_OLD_MEMBER_MAIL_LISTS));
        
        //check if there are mailists specified
        if ($oldMemberMailLists == "") {
            return;
        }
        
        //split the list of maillists
        $oldMemberMailLists = explode(";", $oldMemberMailLists);
                
        //another option besides transforming the setting here is to make the settings follow the maillist id format
        
        //transform the maillist strings to the correct format
        foreach ($oldMemberMailLists as &$mailList) {
            $mailList = str_replace("@", ".", $mailList . env("MAIL_MAN_DOMAIN")); //change the @ to a . to fit the maillist id format
        }
        unset($mailList);//break the reference after the last element
        
        $mailListFacade->addUserToSpecifiedMailLists($this->user->email, $this->user->getName(), $oldMemberMailLists);
    }
}
