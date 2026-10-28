<?php

namespace Tests\Feature\Certificate;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_deleted_certificate_is_hidden_until_restored()
    {
        $user = User::factory()->create();
        $certificate = Certificate::create(['name' => 'Cool', 'abbreviation' => 'Cool']);
        $user->certificates()->attach($certificate);

        $certificate->delete();
        $this->assertEmpty($user->fresh()->certificates);

        $certificate->restore();
        $this->assertTrue($user->fresh()->certificates->contains($certificate));
    }
}
