<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function view(User $actor, User $user): Response
    {
        return $actor->is($user) || $actor->hasRole(Role::Administrator->value, Role::CertificateAdministrator->value)
            ? Response::allow()
            : Response::deny('You do not have sufficient access to view this page');
    }

    public function update(User $actor, User $user): Response
    {
        return $actor->is($user) || $actor->hasRole(Role::Administrator->value)
            ? Response::allow()
            : Response::deny('You do not have sufficient access to view this page');
    }
}
