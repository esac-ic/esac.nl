<?php

namespace Tests\Feature\Migrations;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\TestsMigrations;
use Tests\TestCase;

class StoreMemberRolesOnUsersTest extends TestCase
{
    use TestsMigrations;

    #[Test]
    public function a_deleted_role_is_not_carried_over()
    {
        $migration = require database_path('migrations/2026_10_26_100100_store_member_roles_on_users.php');
        $user = User::factory()->create();
        $migration->down();
        DB::table('roles')->where('id', 1)->update(['deleted_at' => now()]);
        DB::table('role_user')->insert([['user_id' => $user->id, 'role_id' => 1], ['user_id' => $user->id, 'role_id' => 2]]);

        $migration->up();

        $this->assertSame(['content_administrator'], json_decode(DB::table('users')->where('id', $user->id)->value('roles')));
    }
}
