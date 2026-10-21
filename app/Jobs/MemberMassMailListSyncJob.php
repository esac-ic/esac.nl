<?php

namespace App\Jobs;

use App\CustomClasses\MailList\MailListFacade;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;

class MemberMassMailListSyncJob implements ShouldQueue
{
    use Queueable;
    
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
            $mailListsPerMemberKind[$kind_of_member] = UpdateMemberTypeMaillists::getMemberTypeMailLists($kind_of_member);
        }
        
        foreach ($this->users as $user)
        {
            if ($mailListsPerMemberKind[$user->kind_of_member] ?? false) {
                $mailListFacade->addUserToSpecifiedMailLists($user->email, $user->getName(), $mailListsPerMemberKind[$user->kind_of_member]);
            }
        }
        
        \Log::info("Mass mail list sync finished");
    }
}
