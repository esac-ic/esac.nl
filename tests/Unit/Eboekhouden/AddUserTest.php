<?php

namespace Tests\Unit\Eboekhouden;

use App\CustomClasses\Eboekhouden\Eboekhouden;
use App\CustomClasses\Eboekhouden\EboekhoudenFacade;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use TestCase;

class AddUserTest extends TestCase {
    use RefreshDatabase;

    protected function get_relation_return($id) {
        return collect([
            "id" => 1,
            "type" => "B",
            "code" => "lid_{$id}",
            "date" => "2026-10-28T00:00:00+01:00",
            "name" => "Company",
            "salutation" => "Mr.",
            "contact" => "John Doe",
            "gender" => "m",
            "address" => "Company Street 2",
            "postalCode" => "1000 AB",
            "city" => "Amsterdam",
            "country" => "Netherlands",
            "address2" => "Secondary Street 5",
            "postalCode2" => "2000 CD",
            "city2" => "Rotterdam",
            "country2" => "Netherlands",
            "phoneNumber" => "+31612345678",
            "mobilePhoneNumber" => "+31687654321",
            "faxNumber" => "+31201234567",
            "emailAddress" => "contact@company.com",
            "emailAddressInvoice" => "invoice@company.com",
            "emailAddressReminder" => "reminder@company.com",
            "website" => "https://www.e-boekhouden.nl/",
            "note" => "This is a test note.",
            "catNumber" => "NL123456789B01",
            "inactive" => false,
            "termOfPayment" => 30,
            "companyRegistrationNumber" => "12345678",
            "iban" => "NL91ABNA0417164300",
            "freeText1" => "",
            "freeText2" => "",
            "freeText3" => "",
            "freeText4" => "",
            "freeText5" => "",
            "freeText6" => "",
            "freeText7" => "",
            "freeText8" => "",
            "freeText9" => "",
            "freeText10" => "",
            "isClubOrAssociationMember" => 0,
            "doNotReceiveNewsletters" => 0,
            "ledgerId" => 101,
            "mandate" => true,
            "mandateType" => "D",
            "mandateId" => "MAND12345",
            "mandateSignedDate" => "2023-10-01",
        ]);
    }

    public function test_member() {
        $user = User::factory()->member()->create();
        $id = $user->id;

        $this->mock(Eboekhouden::class, function (MockInterface $mock) use($id) {
            $mock->shouldReceive('get')->once()->andReturn(collect(['count' => 0, 'items' => []]));
            $mock->shouldReceive('post')->once()->andReturn($this->get_relation_return($id));
        });

        $facade = $this->app->make(EboekhoudenFacade::class);
        $facade->syncRelation($user);
    }

    public function test_reunist() {
        $user = User::factory()->reunist()->create();
        $id = $user->id;

        $this->mock(Eboekhouden::class, function (MockInterface $mock) use($id) {
            $mock->shouldReceive('get')->once()->andReturn(collect(['count' => 0, 'items' => []]));
            $mock->shouldReceive('post')->once()->andReturn($this->get_relation_return($id));
        });

        $facade = $this->app->make(EboekhoudenFacade::class);
        $facade->syncRelation($user);
    }

    protected function test_too_long($field, $length) {
        $this->expectException(Exception::class);

        $this->mock(Eboekhouden::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')->once()->andReturn(collect(['count' => 0, 'items' => []]));
        });

        $user = User::factory()->member()->create();
        $user[$field] = str_repeat('a', $length);
        $facade = $this->app->make(EboekhoudenFacade::class);
        $facade->syncRelation($user);
    }

    public function test_name_too_long() {
        $this->test_too_long('firstname', 100);
    }
    public function test_street_too_long() {
        $this->test_too_long('houseNumber', 150);
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
    public function test_phonenumber_too_long() {
        $this->test_too_long('phonenumber', 50);
    }
    public function test_email_too_long() {
        $this->test_too_long('email', 50);
    }
    public function test_iban_too_long() {
        $this->test_too_long('IBAN', 50);
    }

}