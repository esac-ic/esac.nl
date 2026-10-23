<?php

namespace Tests\Feature\PendingMembers;

use App\Events\MemberSignedUp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SignUpFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => true])]);
    }

    #[Test]
    public function a_guest_can_sign_up_with_all_details()
    {
        $body = [
            'email' => 'test@esac.nl',
            'first_name' => 'esac',
            'last_name' => 'esac last name',
            'street' => 'stastion street',
            'house_number' => 3,
            'city' => 'eindhoven',
            'zipcode' => '5781JH',
            'country' => 'NL',
            'phone_number' => 28743939,
            'emergency_number' => 112,
            'emergency_house_number' => '2a',
            'emergency_street' => 'nursery',
            'emergency_city' => 'dead',
            'emergency_zipcode' => '3473JP',
            'emergency_country' => 'NL',
            'birthday' => now()->subCenturies(2)->format('d-m-Y'),
            'iban' => 'BLNDj838474784848',
            'password' => 'a-password',
            'password_confirmation' => 'a-password',
            'direct_debit' => 1,
            'privacy_policy' => 1,
            'terms_and_conditions' => 1,
            'g-recaptcha-response' => 'ikbenvalid',
        ];

        $this->post('/sign-up', $body)->assertRedirect();

        Event::assertDispatched(MemberSignedUp::class);
        $this->assertDatabaseHas(User::class, [
            ...Arr::except($body, ['birthday', 'password', 'password_confirmation', 'privacy_policy', 'terms_and_conditions', 'g-recaptcha-response']),
            'member_type' => 'member',
        ]);
        $user = User::sole();
        $this->assertEquals($body['birthday'], $user->birthday->format('d-m-Y'));
        $this->assertNotNull($user->pending_since);
    }

    #[Test]
    public function a_sign_up_needs_the_required_fields()
    {
        $this->post('/sign-up')
            ->assertRedirect()
            ->assertInvalid([
                'email' => 'The email field is required.',
                'first_name' => 'The first name field is required.',
                'last_name' => 'The last name field is required.',
                'street' => 'The street field is required.',
                'house_number' => 'The house number field is required.',
                'city' => 'The city field is required.',
                'phone_number' => 'The phone number field is required.',
                'emergency_number' => 'The emergency phone number field is required.',
                'emergency_street' => 'The emergency address street field is required.',
                'emergency_zipcode' => 'The emergency postal code field is required.',
                'emergency_country' => 'The emergency country field is required.',
                'birthday' => 'The date of birth field is required.',
                'iban' => 'The IBAN field is required.',
                'g-recaptcha-response' => "Please tick 'I'm not a robot'.",
                'direct_debit' => 'The direct debit field must be accepted.',
                'privacy_policy' => 'The Privacy Policy field must be accepted.',
                'terms_and_conditions' => 'The Terms and Conditions field must be accepted.',
            ]);

        $this->assertCount(22, session('errors'));
        Event::assertNotDispatched(MemberSignedUp::class);
        $this->assertDatabaseEmpty(User::class);
    }
}
