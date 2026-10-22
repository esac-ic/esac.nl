<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Rol;
use Illuminate\Database\Seeder;

/**
 * The roles, with the ids the Role enum uses.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Rol::query()->upsert(
            collect(Role::cases())->map(fn (Role $role) => ['id' => $role->value, 'name' => str($role->name)->headline()->lower()->ucfirst()])->all(),
            ['id'],
        );
    }
}
