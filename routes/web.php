<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EditorImageController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventRegistrationController;
use App\Http\Controllers\Admin\FormerMemberController;
use App\Http\Controllers\Admin\MailListController;
use App\Http\Controllers\Admin\MailListMemberController;
use App\Http\Controllers\Admin\MemberCertificateController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MemberExportController;
use App\Http\Controllers\Admin\MemberLogController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\NewsItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PendingMemberController;
use App\Http\Controllers\Admin\PennoExportController;
use App\Http\Controllers\Admin\RegistrationFormController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\EventFeedController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/', '/home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('members/export', MemberExportController::class)->name('members.export');
    Route::singleton('members.membership', MembershipController::class)->creatable()->only(['store', 'destroy'])->parameters(['members' => 'user']);
    Route::get('former-members/export', [FormerMemberController::class, 'export'])->name('former-members.export');
    Route::resource('former-members', FormerMemberController::class)->only('index');
    Route::resource('penno-export', PennoExportController::class)->only(['create', 'store']);

    Route::resource('pending-members', PendingMemberController::class)->only(['index', 'destroy'])->parameters(['pending-members' => 'user']);
    Route::patch('pending-members/{user}/approve', [PendingMemberController::class, 'approve'])->name('pending-members.approve');

    Route::resource('members', MemberController::class)->except('destroy')->parameters(['members' => 'user']);

    Route::resource('certificates', CertificateController::class);
    Route::scopeBindings()->group(fn () => Route::resource('members.certificates', MemberCertificateController::class)->except(['index', 'show'])->parameters(['members' => 'user']));

    Route::resource('roles', RoleController::class);

    Route::resource('events', EventController::class);
    Route::post('events/{event}/copy', [EventController::class, 'copy'])->name('events.copy');
    Route::resource('registration-forms', RegistrationFormController::class);

    Route::get('events/{event}/registrations/export', [EventRegistrationController::class, 'export'])->name('events.registrations.export');
    Route::resource('events.registrations', EventRegistrationController::class)->only(['index', 'create', 'store', 'show', 'destroy'])->scoped();

    Route::resource('event-categories', EventCategoryController::class);
    Route::resource('news-items', NewsItemController::class);

    Route::resource('pages', PageController::class);
    Route::patch('pages/{page}/move', [PageController::class, 'move'])->name('pages.move');
    Route::controller(EditorImageController::class)->prefix('editor-images')->name('editor-images.')->group(function () {
        Route::post('/', 'store')->name('store');
    });

    Route::get('books/export', [BookController::class, 'export'])->name('books.export');
    Route::resource('books', BookController::class);

    Route::post('mail-lists/sync', [MailListController::class, 'sync'])->name('mail-lists.sync');
    Route::resource('mail-lists', MailListController::class)->except(['edit', 'update']);
    Route::resource('mail-lists.members', MailListMemberController::class)->only(['store', 'destroy']);

    Route::controller(MemberLogController::class)->prefix('member-log')->name('member-log.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('export', 'export')->name('export');
    });

    Route::singleton('settings', SettingsController::class)->only(['edit', 'update']);
});

// A member's registration for an event
Route::controller(RegistrationController::class)->prefix('forms/{event}')->name('events.registration.')->group(function () {
    Route::get('/', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::delete('/', 'destroy')->name('destroy');
});

Route::get('signup', [SignUpController::class, 'create'])->name('signup.create');
Route::post('signup', [SignUpController::class, 'store'])->name('signup.store');
Route::get('ical', EventFeedController::class)->name('events.feed');

Route::controller(SiteController::class)->group(function () {
    Route::get('home', 'home')->name('home');
    Route::get('agenda', 'events')->name('events.index');
    Route::get('agenda/{event}', 'showEvent')->name('events.show');
    Route::get('news', 'news')->name('news.index');
    Route::get('news/{newsItem}', 'showNewsItem')->name('news.show');
    Route::get('library', 'library')->name('library');
    Route::get('memberlist', 'members')->name('members.index');
    Route::get('zekeringen', 'zekeringen')->name('zekeringen');
    Route::get('{menuItem:slug}', 'showPage')->name('pages.show');
});
