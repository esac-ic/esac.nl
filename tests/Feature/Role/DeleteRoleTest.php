<?php

namespace Tests\Feature\Role;

use App\Enums\Role;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    private $url = 'rols';

    /**
     * @var
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Role::Administrator->value);
        $this->be($user);

        session()->start();
    }

    #[Test]
    public function a_role_can_be_deleted_by_an_admin()
    {
        $role = Rol::factory()->create();

        $response = $this->delete($this->url . '/' . $role->id, ['_token' => csrf_token()]);

        $response->assertStatus(302);

        $this->assertNull(Rol::find($role->id));
    }

    #[Test]
    public function a_user_can_not_delete_a_role()
    {
        $role = Rol::factory()->create();
        $this->user->roles()->detach();

        $response = $this->delete($this->url . '/' . $role->id, ['_token' => csrf_token()]);

        $response->assertStatus(403);

    }
}
