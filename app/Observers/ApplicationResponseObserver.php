<?php

namespace App\Observers;

use App\Models\ApplicationForm\ApplicationResponse;

class ApplicationResponseObserver
{
    public function deleting(ApplicationResponse $applicationResponse): void
    {
        $applicationResponse->getApplicationFormResponseRows()->forceDelete();
    }
}
