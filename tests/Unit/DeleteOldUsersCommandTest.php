<?php

namespace Tests\Unit;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use TestCase;

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
        $this->assertEquals(null, $user->houseNumber);
        $this->assertEquals(null, $user->city);
        $this->assertEquals(null, $user->zipcode);
        $this->assertEquals(null, $user->country);
        $this->assertEquals(null, $user->phonenumber);
        $this->assertEquals(null, $user->phonenumber_alt);
        $this->assertEquals(null, $user->emergencyNumber);
        $this->assertEquals(null, $user->emergencyHouseNumber);
        $this->assertEquals(null, $user->emergencystreet);
        $this->assertEquals(null, $user->emergencycity);
        $this->assertEquals(null, $user->emergencyzipcode);
        $this->assertEquals(null, $user->emergencycountry);
        $this->assertEquals(null, $user->birthDay);
        $this->assertEquals(null, $user->kind_of_member);
        $this->assertEquals(null, $user->IBAN);
        $this->assertEquals(null, $user->BIC);
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
        $this->assertNotEquals(null, $user->houseNumber);
        $this->assertNotEquals(null, $user->city);
        $this->assertNotEquals(null, $user->zipcode);
        $this->assertNotEquals(null, $user->country);
        $this->assertNotEquals(null, $user->phonenumber);
        $this->assertNotEquals(null, $user->phonenumber_alt);
        $this->assertNotEquals(null, $user->emergencyNumber);
        $this->assertNotEquals(null, $user->emergencyHouseNumber);
        $this->assertNotEquals(null, $user->emergencystreet);
        $this->assertNotEquals(null, $user->emergencycity);
        $this->assertNotEquals(null, $user->emergencyzipcode);
        $this->assertNotEquals(null, $user->emergencycountry);
        $this->assertNotEquals(null, $user->birthDay);
        $this->assertNotEquals(null, $user->kind_of_member);
        $this->assertNotEquals(null, $user->IBAN);
        $this->assertNotEquals(null, $user->remark);
    }
}
