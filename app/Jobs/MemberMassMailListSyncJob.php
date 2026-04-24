<?php

namespace App\Jobs;

use App\CustomClasses\MailList\MailListFacade;
use App\Listeners\UpdateMemberTypeMaillists;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MemberMassMailListSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(
        public Collection $users
    )
    {
    }
    
    public function handle(MailListFacade $mailListFacade): void
    {
        \Log::info("Performing a mass mail list sync");
        
        //get maillists for each member type
        $mailListsPerMemberKind = [];
        foreach (User::KINDS_OF_MEMBER as $kind_of_member) {
            $mailListsPerMemberKind[\Lang::get($kind_of_member)] = UpdateMemberTypeMaillists::getMemberTypeMailLists(\Lang::get($kind_of_member));
        }
        
        foreach ($this->users as $user)
        {
            if ($mailListsPerMemberKind[\Lang::get($user->kind_of_member)] ?? false) {
                $mailListFacade->addUserToSpecifiedMailLists($user->email, $user->getName(), $mailListsPerMemberKind[$user->kind_of_member]);
            }
        }
        
        \Log::info("Mass mail list sync finished");
    }
}
