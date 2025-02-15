<?php

namespace App\Listeners;

use App\Events\MemberTypeChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateMemberTypeMaillists implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MemberTypeChanged $event): void
    {
        //
    }
}
