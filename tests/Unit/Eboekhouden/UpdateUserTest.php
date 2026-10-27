<?php

namespace Tests\Unit\Eboekhouden;

use App\CustomClasses\Eboekhouden\Eboekhouden;
use App\CustomClasses\Eboekhouden\EboekhoudenFacade;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use TestCase;

class UpdateUserTest extends TestCase {
    use RefreshDatabase;

    public function test_member() {
        $this->mock(Eboekhouden::class, function (MockInterface $mock) {
            $mock->expects('patch')->once();
        });

        $user = User::factory()->member()->create();
        $facade = $this->app->make(EboekhoudenFacade::class);
        $facade->updateRelation($user);
    }

    public function test_reunist() {
        $this->mock(Eboekhouden::class, function (MockInterface $mock) {
            $mock->expects('patch')->once();
        });

        $user = User::factory()->reunist()->create();
        $facade = $this->app->make(EboekhoudenFacade::class);
        $facade->updateRelation($user);
    }

    protected function test_too_long($field, $length) {
        $this->expectException(Exception::class);

        $this->mock(Eboekhouden::class, function (MockInterface $mock) {

        });

        $user = User::factory()->member()->create();
        $user[$field] = str_repeat('a', $length);
        $facade = $this->app->make(EboekhoudenFacade::class);
        $facade->updateRelation($user);
    }

    public function test_name_too_long() {
        $this->test_too_long('name', 100);
    }
    public function test_street_too_long() {
        $this->test_too_long('housenumber', 150);
    }
    public function test_zipcode_too_long() {
        $this->test_too_long('zipcode', 50);
    }
    public function test_city_too_long() {
        $this->test_too_long('city', 50);
    }
    public function test_country_too_long() {
        $this->test_too_long('country', 50);
    }
    public function test_phoneNumber_too_long() {
        $this->test_too_long('phoneNumber', 50);
    }
    public function test_email_too_long() {
        $this->test_too_long('email', 50);
    }
    public function test_iban_too_long() {
        $this->test_too_long('IBAN', 50);
    }
    public function test_bic_too_long() {
        $this->test_too_long('BIC', 50);
    }

}