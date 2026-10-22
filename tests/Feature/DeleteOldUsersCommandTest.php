<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteOldUsersCommandTest extends TestCase
{
    use RefreshDatabase;

    private const array PERSONAL_DETAILS = [
        'street',
        'house_number',
        'city',
        'zipcode',
        'country',
        'phone_number',
        'phone_number_alt',
        'emergency_number',
        'emergency_house_number',
        'emergency_street',
        'emergency_city',
        'emergency_zipcode',
        'emergency_country',
        'birthday',
        'kind_of_member',
        'iban',
        'bic',
        'remark',
    ];

    #[Test]
    public function the_details_of_members_who_left_three_years_ago_are_removed()
    {
        $user = User::factory()->create(['membership_ended_on' => now()->subYears(3), 'remember_token' => 'token']);

        $this->artisan('remove:oldUsers');

        $user = User::find($user->id);
        $this->assertNotNull($user->firstname);
        $this->assertNotNull($user->lastname);
        foreach (self::PERSONAL_DETAILS as $attribute) {
            $this->assertNull($user->$attribute, $attribute);
        }
        $this->assertEquals(0, $user->direct_debit);
        $this->assertNull($user->remember_token);
    }

    #[Test]
    public function the_details_of_members_who_left_a_year_ago_are_kept()
    {
        $user = User::factory()->create(['membership_ended_on' => now()->subYear()]);

        $this->artisan('remove:oldUsers');

        $user = User::find($user->id);
        foreach (['firstname', 'lastname', ...self::PERSONAL_DETAILS] as $attribute) {
            $this->assertNotNull($user->$attribute, $attribute);
        }
    }
}
