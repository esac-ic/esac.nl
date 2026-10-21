<?php

namespace Tests\Feature\Agenda;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateAgendaItemTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'agendaItems';
    private $agendaItem;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $this->be($user);

        session()->start();
    }

    #[Test]
    public function CreateAgendaItem()
    {
        //Attach
        $this->user->roles()->attach(Role::ActivityAdministrator->value);

        $agendaItemCategory = AgendaItemCategory::factory()->create();

        $body = [
            '_token' => csrf_token(),
            'title' => 'test agenda',
            'text' => 'test agenda',
            'shortDescription' => 'test agenda',
            'category' => $agendaItemCategory->id,
            'applicationForm' => ApplicationForm::factory()->create()->id,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' => Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(302);
        $agendaItem = AgendaItem::all()->last();

        $this->assertEquals($body['text'], $agendaItem->text);
        $this->assertEquals($body['title'], $agendaItem->title);
        $this->assertEquals($body['category'], $agendaItem->agendaItemCategory->id);
    }

    #[Test]
    public function creating_an_agenda_item_stores_a_resized_thumbnail()
    {
        Storage::fake('public');
        $this->user->roles()->attach(Role::ActivityAdministrator->value);

        $response = $this->post($this->url, [
            'title' => 'test agenda',
            'text' => 'test agenda',
            'shortDescription' => 'test agenda',
            'category' => AgendaItemCategory::factory()->create()->id,
            'applicationForm' => 0,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' => Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
            'thumbnail' => UploadedFile::fake()->image('photo.png', 1000, 1000),
        ]);
        $response->assertStatus(302);
        $agendaItem = AgendaItem::all()->last();

        $this->assertSame([400, 300], array_slice(getimagesize(Storage::disk('public')->path($agendaItem->image_url)), 0, 2));
    }

    public function CreateAgendaWithIncorrectRole()
    {
        //Login as User with incorrect role
        $this->user->roles()->detach();

        $agendaItemCategory = AgendaItemCategory::factory()->create();
        $body = [
            '_token' => csrf_token(),
            'title' => 'test agenda',
            'text' => 'test agenda',
            'shortDescription' => 'test agenda',
            'category' => $agendaItemCategory->id,
            'applicationForm' => ApplicationForm::factory()->create()->id,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' => Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(403);

    }

    public function CreateAgendaWithEmptyFields()
    {
        $this->user->roles()->attach(Role::ActivityAdministrator->value);

        $agendaItemCategory = AgendaItemCategory::factory()->create();
        $body = [
            '_token' => csrf_token(),
            'title' => '',
            'text' => 'test agenda',
            'shortDescription' => 'test agenda',
            'category' => $agendaItemCategory->id,
            'applicationForm' => ApplicationForm::factory()->create()->id,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' => Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(500);
    }

    public function CreateAgendaItemWithEmoji()
    {
        //Attach
        $this->user->roles()->attach(Role::ActivityAdministrator->value);

        $agendaItemCategory = AgendaItemCategory::factory()->create();

        $body = [
            '_token' => csrf_token(),
            'title' => 'test agenda 😀',
            'text' => 'test agenda 😀',
            'shortDescription' => 'test agenda 😀',
            'category' => $agendaItemCategory->id,
            'applicationForm' => ApplicationForm::factory()->create()->id,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' => Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(302);
        $agendaItem = AgendaItem::all()->last();

        $this->assertEquals($body['text'], $agendaItem->text);
        $this->assertEquals($body['title'], $agendaItem->title);
        $this->assertEquals($body['category'], $agendaItem->agendaItemCategory->id);
    }
}
