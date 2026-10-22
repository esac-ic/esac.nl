<?php

namespace App\Policies;

use App\Models\MenuItem;
use App\Models\User;

class MenuItemPolicy
{
    /**
     * Pages marked "login" are for members only.
     */
    public function view(?User $user, MenuItem $menuItem): bool
    {
        return ! $menuItem->login || $user !== null;
    }
}
