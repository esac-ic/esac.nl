<?php

namespace App\Providers;

use App\Enums\AdminRole;
use App\Enums\SettingKey;
use App\Models\Registration;
use App\Models\Setting;
use App\Models\User;
use App\View\Composers\MenuComposer;
use Illuminate\Auth\Access\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Once;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Intl\Countries;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->defineGates();

        Paginator::useBootstrapFour();

        // A queue worker runs many jobs in one process, so each job reads the settings afresh.
        Queue::before(fn () => Once::flush());

        // Kosovo has no official ISO code yet, so it's only listed with the user-assigned XK.
        Countries::withUserAssigned(true);

        View::composer('components.menu', MenuComposer::class);
        View::composer(['site.signup', 'admin.members.form', 'admin.members.show'], fn ($view) => $view->with('countries', Countries::getNames()));
        View::composer('includes.footer', fn ($view) => $view->with(
            'footerPhoneNumber',
            Setting::valueOf(SettingKey::FooterPhoneNumber),
        ));
    }

    /**
     * The abilities of the management pages and which roles have them.
     */
    private function defineGates(): void
    {
        Gate::defaultDenialResponse(Response::deny('You do not have sufficient access to view this page'));

        Gate::define('access-admin', fn (User $user) => $user->hasAnyRole());

        Gate::define('view-members', fn (User $user) => $user->hasRole(AdminRole::Administrator, AdminRole::CertificateAdministrator));
        Gate::define('manage-members', fn (User $user) => $user->hasRole(AdminRole::Administrator));
        Gate::define('manage-roles', fn (User $user) => $user->hasRole(AdminRole::Administrator));
        Gate::define('manage-certificates', fn (User $user) => $user->hasRole(AdminRole::Administrator, AdminRole::CertificateAdministrator));

        Gate::define('manage-events', fn (User $user) => $user->hasRole(AdminRole::ContentAdministrator, AdminRole::EventAdministrator));
        Gate::define('manage-event-categories', fn (User $user) => $user->hasRole(AdminRole::Administrator));
        Gate::define('delete-registration', fn (User $user, Registration $registration) => $registration->user()->is($user)
            || $user->hasRole(AdminRole::EventAdministrator));
        Gate::define('manage-zekeringen', fn (User $user) => $user->hasRole(AdminRole::EventAdministrator));

        Gate::define('manage-news', fn (User $user) => $user->hasRole(AdminRole::ContentAdministrator, AdminRole::EventAdministrator));
        Gate::define('manage-pages', fn (User $user) => $user->hasRole(AdminRole::ContentAdministrator));
        Gate::define('manage-library', fn (User $user) => $user->hasRole(AdminRole::Administrator));

        Gate::define('manage-mail-lists', fn (User $user) => $user->hasRole(AdminRole::Administrator));
        Gate::define('view-member-log', fn (User $user) => $user->hasRole(AdminRole::Administrator));
        Gate::define('manage-settings', fn (User $user) => $user->hasRole(AdminRole::Administrator));
    }
}
