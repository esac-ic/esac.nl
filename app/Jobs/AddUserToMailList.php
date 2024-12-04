<?php

namespace App\Jobs;

use App\User;
use Illuminate\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddUserToMailList implements ShouldQueue
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
    public function handle()
    {
        $currentMemberMailLists = app(\App\Setting::SINGELTONNAME)->getSetting(\App\Setting::SETTING_CURRENT_MEMBER_MAIL_LISTS);
        \Log::info("add " . $this->user->firstname . " to mailists: " . $currentMemberMailLists);
    }
}
