<?php

namespace Tests\Feature\Admin;

use App\Enums\AdminRole;
use App\Models\HeaderSlide;
use App\Models\Sponsor;
use App\Models\User;
use App\Settings\ContactSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->actingAs(User::factory()->withRole(AdminRole::ContentAdministrator)->create());
    }

    #[Test]
    public function header_slides_can_be_managed_and_show_on_the_home_page()
    {
        $this->post('admin/header-slides', [
            'heading' => 'Climbing weekends',
            'message' => 'Over 20 weekends a year.',
            'link' => '/events',
            'image' => UploadedFile::fake()->image('photo.jpg', 3000, 2000),
        ])->assertRedirect('admin/header-slides');
        $slide = HeaderSlide::sole();

        $this->assertSame([1620, 1080], array_slice(getimagesize($slide->getFirstMediaPath('image', 'wide')), 0, 2));
        $this->get('admin/header-slides')->assertOk()->assertSee('Climbing weekends');
        $this->get('/')->assertOk()->assertSee('Climbing weekends')->assertSee($slide->getFirstMediaUrl('image', 'wide'));

        $this->patch("admin/header-slides/{$slide->id}", ['heading' => 'Bouldering', 'message' => 'Fontainebleau.', 'link' => '/events'])
            ->assertRedirect('admin/header-slides');
        $this->assertSame('Bouldering', $slide->refresh()->heading);
        $this->assertTrue($slide->hasMedia('image'));

        $this->delete("admin/header-slides/{$slide->id}")->assertRedirect('admin/header-slides');
        $this->assertSame(0, HeaderSlide::query()->count());
        $this->get('/')->assertOk();
    }

    #[Test]
    public function sponsors_can_be_managed_and_show_in_the_footer()
    {
        $this->post('admin/sponsors', [
            'name' => 'Klimwinkel',
            'url' => 'https://www.klimwinkel.nl',
            'logo' => UploadedFile::fake()->image('logo.png', 800, 300),
        ])->assertRedirect('admin/sponsors');
        $sponsor = Sponsor::sole();

        $this->get('/')->assertSee('https://www.klimwinkel.nl')->assertSee($sponsor->getFirstMediaUrl('logo', 'footer'));

        $this->delete("admin/sponsors/{$sponsor->id}")->assertRedirect('admin/sponsors');
        $this->get('/')->assertDontSee('https://www.klimwinkel.nl');
    }

    #[Test]
    public function the_contact_details_in_the_footer_can_be_changed()
    {
        $this->put('admin/contact-details', [
            'phone_number' => '040 123 4567',
            'email' => 'board@esac.nl',
            'iban' => 'NL00 TEST 0000 0000 00',
            'kvk_number' => '12345678',
            'facebook_url' => '',
            'instagram_url' => 'https://www.instagram.com/esac.nl/',
            'youtube_url' => '',
        ])->assertRedirect('admin/contact-details/edit');

        $this->assertSame('board@esac.nl', app(ContactSettings::class)->refresh()->email);
        $this->get('/')->assertSee('040 123 4567')->assertSee('board@esac.nl')->assertSee('instagram.com/esac.nl')->assertDontSee('facebook.com');
    }

    #[Test]
    public function only_content_managers_manage_the_website()
    {
        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator)->create());

        $this->get('admin/header-slides')->assertForbidden();
        $this->get('admin/sponsors')->assertForbidden();
        $this->get('admin/contact-details/edit')->assertForbidden();
    }

    #[Test]
    public function a_photo_can_have_at_most_24_megapixels()
    {
        // Only the header of a 7000×4000 PNG: the rule reads the size without decoding the pixels.
        $header = "\x89PNG\r\n\x1a\n".pack('N', 13).'IHDR'.pack('NNCCCCC', 7000, 4000, 8, 2, 0, 0, 0);
        $photo = UploadedFile::fake()->createWithContent('large.png', $header.pack('N', crc32(substr($header, 12))));

        $this->post('admin/header-slides', ['heading' => 'Large', 'message' => 'Too large.', 'link' => '/events', 'image' => $photo])
            ->assertInvalid(['image' => 'at most 24 megapixels']);
    }
}
