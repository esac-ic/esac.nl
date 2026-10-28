<?php

namespace Tests\Feature\Certificate;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

class DeleteCertificateTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var string
     */
    private $url = 'certificates';

    /**
     * @var
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Config::get('constants.Certificate_administrator'));
        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /** @test */
    public function a_deleted_certificate_is_hidden_from_its_holders_but_can_be_restored()
    {
        $certificate = Certificate::create(['name' => 'Certified cool person', 'abbreviation' => 'Cool']);
        $holder = User::factory()->create();
        $holder->certificates()->attach($certificate->id);

        $response = $this->delete($this->url . '/' . $certificate->id, ['_token' => csrf_token()]);

        $response->assertStatus(302);
        $this->assertEmpty($holder->fresh()->certificates);
        $this->assertEquals('', $holder->fresh()->getCertificationsAbbreviations());

        $certificate->restore();

        $this->assertTrue($holder->fresh()->certificates->contains($certificate));
    }
}
