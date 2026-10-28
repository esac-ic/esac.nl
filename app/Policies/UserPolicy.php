<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $actor, User $user): bool
    {
        return $actor->is($user) || $actor->can('view-members');
    }

    public function update(User $actor, User $user): bool
    {
        return $actor->is($user) || $actor->can('manage-members');
    }
}
