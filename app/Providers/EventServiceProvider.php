<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\PendingUserApproved::class => [
            \App\Listeners\LogPendingUserApproved::class,
        ],
        \App\Events\PendingUserRemoved::class => [
            \App\Listeners\LogPendingUserRemoved::class,
        ],
        \App\Events\MemberBecameOldMember::class => [
            \App\Listeners\LogMemberBecameOldMember::class,
        ],
        \App\Events\OldMemberBecameMember::class => [
            \App\Listeners\LogOldMemberBecameMember::class,
        ],
        \App\Events\MemberKindChanged::class => [
            \App\Listeners\LogMemberKindChanged::class,
        ],
        \App\Events\PendingUserCreated::class => [
            \App\Listeners\LogPendingUserCreated::class,
        ],  
    ];
    
    protected $subscribe = [
        \App\Listeners\UpdateMemberTypeMaillists::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
