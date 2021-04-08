<?php

namespace App\Listeners\User;

use App\Events\User\UserApplicationApprovedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeMailListener
{
    /**
     * Handle the event.
     *
     * @param UserApplicationApprovedEvent $event
     * @return void
     */
    public function handle(UserApplicationApprovedEvent $event)
    {
        //
    }
}
