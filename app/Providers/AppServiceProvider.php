<?php

namespace App\Providers;

use App\Enums\Role;
use App\Enums\SettingKey;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\Setting;
use App\Models\User;
use App\View\Composers\MenuComposer;
use Illuminate\Auth\Access\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->defineGates();

        Paginator::useBootstrapFour();

        View::composer('includes.menu', MenuComposer::class);
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

        Gate::define('access-admin', fn (User $user) => $user->hasBackendRights());

        Gate::define('view-members', fn (User $user) => $user->hasRole(Role::Administrator, Role::CertificateAdministrator));
        Gate::define('manage-members', fn (User $user) => $user->hasRole(Role::Administrator));
        Gate::define('manage-roles', fn (User $user) => $user->hasRole(Role::Administrator));
        Gate::define('manage-certificates', fn (User $user) => $user->hasRole(Role::Administrator, Role::CertificateAdministrator));

        Gate::define('manage-agenda', fn (User $user) => $user->hasRole(Role::ContentAdministrator, Role::ActivityAdministrator));
        Gate::define('manage-agenda-categories', fn (User $user) => $user->hasRole(Role::Administrator));
        Gate::define('delete-registration', fn (User $user, ApplicationResponse $registration) => $registration->user_id == $user->id
            || $user->hasRole(Role::ActivityAdministrator));
        Gate::define('manage-zekeringen', fn (User $user) => $user->hasRole(Role::ActivityAdministrator));

        Gate::define('manage-news', fn (User $user) => $user->hasRole(Role::ContentAdministrator, Role::ActivityAdministrator));
        Gate::define('manage-pages', fn (User $user) => $user->hasRole(Role::ContentAdministrator));
        Gate::define('manage-library', fn (User $user) => $user->hasRole(Role::Administrator));

        Gate::define('manage-mail-lists', fn (User $user) => $user->hasRole(Role::Administrator));
        Gate::define('view-user-event-log', fn (User $user) => $user->hasRole(Role::Administrator));
        Gate::define('manage-settings', fn (User $user) => $user->hasRole(Role::Administrator));
    }
}
