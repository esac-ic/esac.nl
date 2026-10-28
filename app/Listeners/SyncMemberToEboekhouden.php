<?php

namespace App\Listeners;

use App\CustomClasses\Eboekhouden\EboekhoudenFacade;
use App\Events\PendingUserRemoved;
use App\Events\UserFinancialsUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use ReflectionClass;

class SyncMemberToEboekhouden implements ShouldQueue
{
    private EboekhoudenFacade $eboekhouden;
    
    /**
     * Create the event listener.
     *
     * @param EboekhoudenFacade $eboekhouden
     */
    public function __construct(EboekhoudenFacade $eboekhouden)
    {
        $this->eboekhouden = $eboekhouden;
    }

    /**
     * Handle the event.
     */
    public function handle(UserFinancialsUpdated $event): void
    {
        $this->eboekhouden->syncRelation($event->user);
    }
}
