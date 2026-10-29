<?php

namespace App\Providers;

use App\Enums\AdminRole;
use App\Models\HeaderSlide;
use App\Models\Sponsor;
use App\Models\User;
use App\Settings\ContactSettings;
use App\View\Composers\MenuComposer;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Intl\Countries;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Lazy loading, unknown attributes and dropped mass assignments fail in development and tests; production stays lenient.
        Model::shouldBeStrict(! $this->app->isProduction());
        DB::prohibitDestructiveCommands($this->app->isProduction());

        $this->defineGates();

        // The member routes only find the members in the state that the route is for.
        Route::bind('pending_member', fn (string $id) => User::query()->pending()->findOrFail($id));
        Route::bind('active_member', fn (string $id) => User::query()->active()->findOrFail($id));
        // Anonymized former members have no email address, so they can't come back.
        Route::bind('former_member', fn (string $id) => User::query()->former()->whereNotNull('email')->findOrFail($id));

        Paginator::useBootstrapFour();

        // Kosovo has no official ISO code yet, so it's only listed with the user-assigned XK.
        Countries::withUserAssigned(true);

        View::composer('components.menu', MenuComposer::class);
        View::composer(['components.member-fields', 'components.member-tabs'], fn ($view) => $view->with('countries', Countries::getNames()));
        View::composer('includes.header', fn ($view) => $view->with('headerSlide', HeaderSlide::query()->with('media')->inRandomOrder()->first()));
        View::composer('includes.footer', fn ($view) => $view->with([
            'contact' => app(ContactSettings::class),
            'sponsors' => Sponsor::query()->with('media')->get(),
        ]));
    }

    /**
     * The abilities of the management pages and the roles that have them.
     */
    private const array ABILITIES = [
        'view-members' => [AdminRole::Administrator, AdminRole::CertificateAdministrator],
        'view-member-details' => [AdminRole::Administrator],
        'manage-members' => [AdminRole::Administrator],
        'manage-roles' => [AdminRole::Administrator],
        'manage-certificates' => [AdminRole::Administrator, AdminRole::CertificateAdministrator],
        'manage-events' => [AdminRole::Administrator, AdminRole::ContentAdministrator, AdminRole::EventAdministrator],
        'manage-event-categories' => [AdminRole::Administrator],
        'manage-registrations' => [AdminRole::Administrator, AdminRole::EventAdministrator],
        'manage-zekeringen' => [AdminRole::Administrator, AdminRole::EventAdministrator],
        'manage-news' => [AdminRole::Administrator, AdminRole::ContentAdministrator, AdminRole::EventAdministrator],
        'manage-pages' => [AdminRole::Administrator, AdminRole::ContentAdministrator],
        // Whoever manages pages, news or events adds images to their text.
        'upload-editor-images' => [AdminRole::Administrator, AdminRole::ContentAdministrator, AdminRole::EventAdministrator],
        'manage-library' => [AdminRole::Administrator],
        'manage-mail-lists' => [AdminRole::Administrator],
        'view-member-log' => [AdminRole::Administrator],
        'manage-settings' => [AdminRole::Administrator],
    ];

    private function defineGates(): void
    {
        Gate::defaultDenialResponse(Response::deny('You do not have sufficient access to view this page'));

        Gate::define('access-admin', fn (User $user) => $user->hasAnyRole());

        foreach (self::ABILITIES as $ability => $roles) {
            Gate::define($ability, fn (User $user) => $user->hasRole(...$roles));
        }
    }
}
