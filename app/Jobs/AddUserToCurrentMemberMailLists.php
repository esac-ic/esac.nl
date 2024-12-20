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
        
        //get all mail lists to check if 
        $allMailLists = $mailListFacade->getAllMailLists();
        $allMailListIds = [];
        
        //make a list of all mailist ids to check if the maillists we want to add the user to exist
        foreach ($allMailLists as $mailList) {
            array_push($allMailListIds, $mailList->getId());
        }
                
        //for each of the active member lists: add as a member if it exists
        foreach ($currentMemberMailLists as $mailList) {
            $mailList = $mailList . env("MAIL_MAN_DOMAIN");

            //check if the mailist exists and then add the user to the mail list
            if (in_array($mailList, $allMailListIds)) {
                $mailListFacade->addMember($mailList, $this->user->email, $this->user->getName());
                \Log::info("add " . $this->user->firstname . " to mailists: " . $mailList);
            } else {
                //TODO: possibly display an error in the gui here somehow
                \Log::error("tried adding user to maillist that doesn't exist");
            }
        }
        
    }
}
