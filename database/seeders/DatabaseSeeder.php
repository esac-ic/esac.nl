<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Enums\FormFieldType;
use App\Models\Book;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\MemberLogEntry;
use App\Models\NewsItem;
use App\Models\Registration;
use App\Models\RegistrationForm;
use App\Models\RegistrationFormField;
use App\Models\User;
use App\Models\Zekering;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

/**
 * Development data for `php artisan migrate:fresh --seed`. Never talks to Mailman.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([RoleSeeder::class, MenuItemSeeder::class]);

        $admin = User::factory()
            ->withRole(AdminRole::Administrator, AdminRole::ContentAdministrator, AdminRole::EventAdministrator)
            ->create(['email' => 'test@esac.nl', 'password' => 'test', 'first_name' => 'Piet', 'preposition' => 'van', 'last_name' => 'Jansen']);
        User::factory()->member()->create(['email' => 'member@esac.nl', 'password' => 'test']);
        $members = User::factory()->count(20)->create();
        User::factory()->count(3)->create(['membership_ended_on' => now()->subYear()]);
        User::factory()->count(2)->create(['pending_since' => today()]);

        $certificates = Certificate::factory()->count(3)->create();
        $members->random(8)->each(fn (User $member) => $member->certificates()->attach($certificates->random()));

        $form = RegistrationForm::factory()
            ->has(RegistrationFormField::factory()->count(3)->sequence(
                ['type' => FormFieldType::Text],
                ['type' => FormFieldType::Number],
                ['type' => FormFieldType::Checkbox],
            ), 'fields')
            ->create();

        $events = Event::factory()
            ->count(8)
            ->for($admin, 'creator')
            ->state(new Sequence(fn (Sequence $sequence) => [
                'start_date' => now()->addWeeks($sequence->index)->setTime(10, 0),
                'end_date' => now()->addWeeks($sequence->index)->setTime(18, 0),
                'registration_deadline' => now()->addWeeks($sequence->index)->subDay(),
                'registration_form_id' => $sequence->index % 2 ? $form->id : null,
            ]))
            ->recycle(EventCategory::factory()->count(3)->create())
            ->create();

        $events->whereNotNull('registration_form_id')->each(fn (Event $event) => $members->random(5)->each(
            fn (User $member) => Registration::factory()->for($member)->for($event)->create(['registration_form_id' => $form->id]),
        ));

        NewsItem::factory()->count(6)->create();
        Book::factory()->count(10)->create();
        Zekering::factory()->count(3)->recycle($admin)->has(Zekering::factory()->count(2), 'children')->create();
        MemberLogEntry::factory()->count(10)->state(fn () => ['user_id' => $members->random()->id])->create();
    }
}
