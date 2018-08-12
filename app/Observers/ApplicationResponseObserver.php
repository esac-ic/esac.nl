<?php
/**
 * Created by PhpStorm.
 * User: Niek
 * Date: 19-6-2018
 * Time: 19:47
 */

namespace App\Observers;


use App\ApplicationResponse;

class ApplicationResponseObserver
{
    public function deleting(ApplicationResponse $applicationResponse){
        $applicationResponse->getApplicationFormResponseRows()->forceDelete();
    }
}