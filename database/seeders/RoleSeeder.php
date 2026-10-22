<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * The roles, with the ids the AdminRole enum uses.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->upsert(
            collect(AdminRole::cases())->map(fn (AdminRole $role) => ['id' => $role->value, 'name' => str($role->name)->headline()->lower()->ucfirst()])->all(),
            ['id'],
        );
    }
}
