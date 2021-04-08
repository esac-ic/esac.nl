<?php

namespace App\Listeners\User;

use App\CustomClasses\MailList\MailList;
use App\CustomClasses\MailList\MailListFacade;
use App\Events\User\UserApplicationApprovedEvent;
use App\Setting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddUserToMailingListsListener
{
    /**
     * @var MailListFacade
     */
    private $mailListFacade;

    /**
     * AddUserToMailingListsListener constructor.
     * @param MailListFacade $mailListFacade
     */
    public function __construct(MailListFacade $mailListFacade)
    {
        $this->mailListFacade = $mailListFacade;
    }


    /**
     * Handle the event.
     *
     * @param UserApplicationApprovedEvent $event
     * @return void
     */
    public function handle(UserApplicationApprovedEvent $event)
    {
        $defaultMailingLists = explode(';', app(Setting::SINGELTONNAME)->getsetting(Setting::SETTING_DEFAULT_MAILING_LISTS_FOR_NEW_USERS));
        $user                = $event->getUser();

        foreach ($defaultMailingLists as $mailingList) {
            $this->mailListFacade->addMember($mailingList, $user->email, $user->getName());
        }
    }
}
