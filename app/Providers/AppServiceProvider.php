<?php

namespace App\Providers;

use App\Enums\AdminRole;
use App\Enums\SettingKey;
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
        View::composer(['site.sign-up', 'components.member-fields', 'components.member-tabs'], fn ($view) => $view->with('countries', Countries::getNames()));
        View::composer('includes.footer', fn ($view) => $view->with(
            'footerPhoneNumber',
            Setting::valueOf(SettingKey::FooterPhoneNumber),
        ));
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
