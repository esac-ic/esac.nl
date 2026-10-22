<?php

namespace Tests\Unit;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DeleteOldUsersCommandTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');

    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    public function testDeleteOldUserWichIsLidAfFor3Years()
    {
        $id = 1;
        $user = User::find($id);
        $user->lid_af = Carbon::now()->subYears(3);
        $user->save();

        Artisan::call("remove:oldUsers");

        $user = User::find($id);
        $this->assertNotEquals(null, $user);
        $this->assertNotEquals(null, $user->firstname);
        $this->assertNotEquals(null, $user->lastname);
        $this->assertEquals(null, $user->street);
        $this->assertEquals(null, $user->house_number);
        $this->assertEquals(null, $user->city);
        $this->assertEquals(null, $user->zipcode);
        $this->assertEquals(null, $user->country);
        $this->assertEquals(null, $user->phone_number);
        $this->assertEquals(null, $user->phone_number_alt);
        $this->assertEquals(null, $user->emergency_number);
        $this->assertEquals(null, $user->emergency_house_number);
        $this->assertEquals(null, $user->emergency_street);
        $this->assertEquals(null, $user->emergency_city);
        $this->assertEquals(null, $user->emergency_zipcode);
        $this->assertEquals(null, $user->emergency_country);
        $this->assertEquals(null, $user->birthday);
        $this->assertEquals(null, $user->kind_of_member);
        $this->assertEquals(null, $user->iban);
        $this->assertEquals(null, $user->bic);
        $this->assertEquals(null, $user->remark);
        $this->assertEquals(0, $user->incasso);
    }

    public function testDeleteOldUserWichIsLidAfFor1Years()
    {
        $id = 1;
        $user = User::find($id);
        $user->lid_af = Carbon::now()->subYears(1);
        $user->save();

        Artisan::call("remove:oldUsers");

        $user = User::find($id);
        $this->assertNotEquals(null, $user);
        $this->assertNotEquals(null, $user->firstname);
        $this->assertNotEquals(null, $user->lastname);
        $this->assertNotEquals(null, $user->street);
        $this->assertNotEquals(null, $user->house_number);
        $this->assertNotEquals(null, $user->city);
        $this->assertNotEquals(null, $user->zipcode);
        $this->assertNotEquals(null, $user->country);
        $this->assertNotEquals(null, $user->phone_number);
        $this->assertNotEquals(null, $user->phone_number_alt);
        $this->assertNotEquals(null, $user->emergency_number);
        $this->assertNotEquals(null, $user->emergency_house_number);
        $this->assertNotEquals(null, $user->emergency_street);
        $this->assertNotEquals(null, $user->emergency_city);
        $this->assertNotEquals(null, $user->emergency_zipcode);
        $this->assertNotEquals(null, $user->emergency_country);
        $this->assertNotEquals(null, $user->birthday);
        $this->assertNotEquals(null, $user->kind_of_member);
        $this->assertNotEquals(null, $user->iban);
        $this->assertNotEquals(null, $user->remark);
    }
}
