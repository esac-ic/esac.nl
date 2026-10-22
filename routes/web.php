<?php

use App\Http\Controllers\AgendaItemCategoryController;
use App\Http\Controllers\AgendaItemController;
use App\Http\Controllers\ApplicationForm\AgendaApplicationFormController;
use App\Http\Controllers\ApplicationForm\ApplicationFormController;
use App\Http\Controllers\ApplicationForm\UserApplicationFormController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\ICalController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MailListController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\MemberExportController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\NewsItemController;
use App\Http\Controllers\OldMemberController;
use App\Http\Controllers\PaginaBeheerController;
use App\Http\Controllers\PendingUserController;
use App\Http\Controllers\PennoExportController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\UserCertificateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserEventLogEntryController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/', '/home');

// Management
Route::prefix('beheer')->name('beheer.')->group(function () {
    Route::get('home', [ManageController::class, 'index'])->name('home');

    Route::get('users/export', MemberExportController::class)->name('users.export');
    Route::singleton('users.membership', MembershipController::class)->creatable()->only(['store', 'destroy']);
    Route::get('old-members/export', [OldMemberController::class, 'export'])->name('old-members.export');
    Route::resource('old-members', OldMemberController::class)->only('index');
    Route::resource('penno-export', PennoExportController::class)->only(['create', 'store']);

    Route::resource('pending-users', PendingUserController::class)->only(['index', 'destroy'])->parameters(['pending-users' => 'user']);
    Route::patch('pending-users/{user}/approve', [PendingUserController::class, 'approve'])->name('pending-users.approve');

    Route::resource('users', UserController::class)->except('destroy');

    Route::resource('certificates', CertificateController::class);
    Route::scopeBindings()->group(fn () => Route::resource('users.certificates', UserCertificateController::class)->except(['index', 'show']));

    Route::resource('roles', RolController::class)->parameters(['roles' => 'rol']);

    Route::resource('agenda-items', AgendaItemController::class);
    Route::post('agenda-items/{agenda_item}/copy', [AgendaItemController::class, 'copy'])->name('agenda-items.copy');
    Route::resource('application-forms', ApplicationFormController::class);

    Route::get('agenda-items/{agenda_item}/registrations/export', [AgendaApplicationFormController::class, 'export'])->name('agenda-items.registrations.export');
    Route::resource('agenda-items.registrations', AgendaApplicationFormController::class)->only(['index', 'create', 'store', 'show', 'destroy'])->scoped();

    Route::resource('agenda-item-categories', AgendaItemCategoryController::class);
    Route::resource('news-items', NewsItemController::class);

    Route::resource('pages', PaginaBeheerController::class);
    Route::controller(StorageController::class)->prefix('images')->name('images.')->group(function () {
        Route::post('/', 'uploadImage')->name('store');
    });

    Route::get('books/export', [LibraryController::class, 'exportLibrary'])->name('books.export');
    Route::resource('books', LibraryController::class);

    Route::resource('mail-lists', MailListController::class)->except(['edit', 'update']);
    Route::controller(MailListController::class)->prefix('mail-lists')->name('mail-lists.')->group(function () {
        Route::post('sync', 'massMemberMailListSync')->name('sync');
        Route::post('{mail_list}/members', 'addMember')->name('members.store');
        Route::delete('{mail_list}/members/{member}', 'deleteMemberFromMailList')->name('members.destroy');
    });

    Route::controller(UserEventLogEntryController::class)->prefix('user-event-log')->name('user-event-log.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('export', 'export')->name('export');
    });

    Route::singleton('settings', SettingsController::class);
});

// Event registrations
Route::controller(UserApplicationFormController::class)->prefix('forms/{agendaItem}')->name('forms.')->group(function () {
    Route::get('/', 'showRegistrationForm')->name('create');
    Route::post('/', 'store')->name('store');
    Route::delete('/', 'unregister')->name('unregister');
});

// Front end
Route::post('signup', [PendingUserController::class, 'store'])->name('front-end.signup.store');
Route::get('ical', [ICalController::class, 'getAgendaItemsICalObject'])->name('front-end.ical');

Route::controller(FrontEndController::class)->name('front-end.')->group(function () {
    Route::get('home', 'home')->name('home');
    Route::get('agenda', 'agenda')->name('agenda');
    Route::get('agenda/{agendaItem}', 'agendaDetailView')->name('agenda.detail');
    Route::get('news', 'news')->name('news');
    Route::get('news/{newsItem}', 'newsDetailView')->name('news.detail');
    Route::get('library', 'library')->name('library');
    Route::get('memberlist', 'memberList')->name('memberlist');
    Route::get('zekeringen', 'zekeringen')->name('zekeringen');
    Route::get('signup', 'publicSubscribe')->name('signup');
    Route::get('{menuItem:url_name}', 'showPage')->name('page');
});
