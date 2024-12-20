<?php

namespace App\Jobs;

use App\CustomClasses\MailList\MailList;
use App\CustomClasses\MailList\MailListFacade;
use App\User;
use Illuminate\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddUserToCurrentMemberMailLists implements ShouldQueue
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
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MailListFacade $mailListFacade)
    {
        //get the value of the setting
        $currentMemberMailLists = explode(";", app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_CURRENT_MEMBER_MAIL_LISTS));
        $mailLists = array();
        
        
        //another option is to make the settings follow the maillist id format
        foreach ($currentMemberMailLists as $mailList) {
            $mailList = $mailList . env("MAIL_MAN_DOMAIN");
            
            array_push($mailLists, str_replace("@", ".", $mailList)); //change the @ to a . to fit the maillist id format
        }
        
        $mailListFacade->addUserToSpecifiedMailLists($this->user->email, $this->user->getName(), $mailLists);
    }
}
