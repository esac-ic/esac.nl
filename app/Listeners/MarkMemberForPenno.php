<?php

namespace App\Listeners;

use App\Events\MemberInfoUpdated;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkMemberForPenno implements ShouldQueue
{
    private UserRepository $userRepository;
    
    /**
     * Create the event listener.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    /**
     * Handle the event.
     */
    public function handle(MemberInfoUpdated $event): void
    {
        $current = $event->user;
        $newData = $event->newData;
        $reasons = [];

        if($current->IBAN != $newData['IBAN']) array_push($reasons, 'IBAN');
        if($current->BIC != $newData['BIC']) array_push($reasons, 'BIC');
        if($current->incasso != $newData['incasso']) array_push($reasons, 'incasso');

        if(!count($reasons)) return;

        $current->markAsTreasurerPending($reasons);
    }
}