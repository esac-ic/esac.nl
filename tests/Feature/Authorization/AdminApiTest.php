<?php

namespace Tests\Feature\Authorization;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[TestWith(['api/users'])]
    #[TestWith(['api/users/autoComplete?term=a'])]
    public function a_member_without_an_admin_role_cannot_use_the_admin_api(string $url)
    {
        $this->actingAs(User::factory()->create())->get($url)->assertForbidden();
    }
}
