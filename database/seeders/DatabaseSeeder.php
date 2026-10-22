<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Enums\FormRowType;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\Book;
use App\Models\Certificate;
use App\Models\NewsItem;
use App\Models\User;
use App\Models\UserEventLogEntry;
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
            ->withRole(AdminRole::Administrator, AdminRole::ContentAdministrator, AdminRole::ActivityAdministrator)
            ->create(['email' => 'test@esac.nl', 'password' => 'test', 'firstname' => 'Piet', 'preposition' => 'van', 'lastname' => 'Jansen']);
        User::factory()->member()->create(['email' => 'member@esac.nl', 'password' => 'test']);
        $members = User::factory()->count(20)->create();
        User::factory()->count(3)->create(['membership_ended_on' => now()->subYear()]);
        User::factory()->count(2)->create(['pending_since' => today()]);

        $certificates = Certificate::factory()->count(3)->create();
        $members->random(8)->each(fn (User $member) => $member->certificates()->attach($certificates->random()));

        $form = ApplicationForm::factory()
            ->has(ApplicationFormRow::factory()->count(3)->sequence(
                ['type' => FormRowType::Text],
                ['type' => FormRowType::Number],
                ['type' => FormRowType::CheckBox],
            ), 'applicationFormRows')
            ->create();

        $agendaItems = AgendaItem::factory()
            ->count(8)
            ->for($admin, 'creator')
            ->state(new Sequence(fn (Sequence $sequence) => [
                'start_date' => now()->addWeeks($sequence->index)->setTime(10, 0),
                'end_date' => now()->addWeeks($sequence->index)->setTime(18, 0),
                'subscription_end_date' => now()->addWeeks($sequence->index)->subDay(),
                'application_form_id' => $sequence->index % 2 ? $form->id : null,
            ]))
            ->recycle(AgendaItemCategory::factory()->count(3)->create())
            ->create();

        $agendaItems->whereNotNull('application_form_id')->each(fn (AgendaItem $agendaItem) => $members->random(5)->each(
            fn (User $member) => ApplicationResponse::factory()->for($member)->for($agendaItem)->create(['application_form_id' => $form->id]),
        ));

        NewsItem::factory()->count(6)->create();
        Book::factory()->count(10)->create();
        Zekering::factory()->count(3)->recycle($admin)->has(Zekering::factory()->count(2), 'children')->create();
        UserEventLogEntry::factory()->count(10)->state(fn () => ['user_id' => $members->random()->id])->create();
    }
}
