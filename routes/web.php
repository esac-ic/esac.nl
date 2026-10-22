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
use App\Http\Controllers\NewsItemController;
use App\Http\Controllers\PaginaBeheerController;
use App\Http\Controllers\PendingUserController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\UserCertificateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserEventLogEntryController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/', '/home');

// Management
Route::group(['as' => 'beheer.', 'prefix' => 'beheer/'], function () {
    Route::get('home', [ManageController::class, 'index'])->name('home');
    Route::resource('applicationForms', ApplicationFormController::class);

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/edit', [SettingsController::class, 'edit'])->name('settings.edit');
});

// Members
Route::get('users/old_members', [UserController::class, 'indexOldMembers'])->name('users.index-old-members');
Route::get('users/pending_members', [PendingUserController::class, 'indexPendingMembers'])->name('users.indexPendingMembers');
Route::get('users/exportUsers', [UserController::class, 'exportUsers'])->name('users.exportUsers');
Route::get('users/pennoMemberExportView', [UserController::class, 'pennoMemberExportSelect'])->name('users.pennoMemberExportView');
Route::get('users/pennoMemberExport', [UserController::class, 'pennoMemberExport'])->name('users.pennoMemberExport');
Route::get('users/exportOldUsers', [UserController::class, 'exportOldUsers'])->name('users.exportOldUsers');
Route::patch('users/{user}/removeAsActiveMember', [UserController::class, 'removeAsActiveMember'])->name('users.removeAsActiveMember');
Route::patch('users/{user}/makeActiveMember', [UserController::class, 'makeActiveMember'])->name('users.makeActiveMember');
Route::patch('users/{user}/removeAsPendingMember', [PendingUserController::class, 'removeAsPendingMember'])->name('users.removeAsPendingMember');
Route::patch('users/{user}/approveAsPendingMember', [PendingUserController::class, 'approveAsPendingMember'])->name('users.approveAsPendingMember');

// Member certificates
Route::get('users/{user}/addCertificate', [UserCertificateController::class, 'addCertificate'])->name('users.certificates.create');
Route::get('users/{user}/addCertificate/{certificate}', [UserCertificateController::class, 'editUserCertificate'])->name('users.certificates.edit');
Route::post('users/{user}/addCertificate', [UserCertificateController::class, 'saveCertificate'])->name('users.certificates.store');
Route::patch('users/{user}/addCertificate/{certificate}', [UserCertificateController::class, 'updateUserCertificate'])->name('users.certificates.update');
Route::delete('users/{user}/addCertificate/{certificate}', [UserCertificateController::class, 'deleteUserCertificate'])->name('users.certificates.destroy');

// Library
Route::get('books/exportLibrary', [LibraryController::class, 'exportLibrary'])->name('books.export');

// Resources
Route::resource('users', UserController::class);
Route::resource('rols', RolController::class);
Route::resource('pages', PaginaBeheerController::class);
Route::resource('certificates', CertificateController::class);
Route::resource('agendaItems', AgendaItemController::class);
Route::resource('agendaItemCategories', AgendaItemCategoryController::class);
Route::resource('newsItems', NewsItemController::class);
Route::resource('mailList', MailListController::class)->except(['edit', 'update']);
Route::post('/signup', [PendingUserController::class, 'storePendingUser'])->name('user.signup');
Route::resource('books', LibraryController::class);
Route::post('images/upload', [StorageController::class, 'uploadImage'])->name('images.store');
Route::delete('images/delete', [StorageController::class, 'deleteImage'])->name('images.destroy');
Route::post('agendaItems/{agendaItem}/copy', [AgendaItemController::class, 'copy'])->name('copyAgendaItem');

Route::get('userEventLog', [UserEventLogEntryController::class, 'index'])->name('user-event-log-entry.index');
Route::post('userEventLog/export', [UserEventLogEntryController::class, 'export'])->name('user-event-log-entry.export');

// Event registrations
Route::get('forms/{agendaItem}', [UserApplicationFormController::class, 'showRegistrationForm'])->name('forms.create');
Route::delete('forms/{agendaItem}', [UserApplicationFormController::class, 'unregister'])->name('forms.unregister');
Route::post('forms/{agendaItem}', [UserApplicationFormController::class, 'store'])->name('forms.store');
Route::get('forms/admin/{agendaItem}', [AgendaApplicationFormController::class, 'registerUser'])->name('forms.admin.create');
Route::post('forms/admin/{agendaItem}', [AgendaApplicationFormController::class, 'saveRegistration'])->name('forms.admin.store');
Route::get('forms/users/{agendaItem}', [AgendaApplicationFormController::class, 'index'])->name('forms.users.index');
Route::get('forms/users/{user}/detail/{agendaItem}', [AgendaApplicationFormController::class, 'show'])->name('forms.users.show');
Route::get('forms/users/{agendaItem}/export', [AgendaApplicationFormController::class, 'exportData'])->name('forms.users.export');
Route::delete('forms/{agendaItem}/remove/{applicationResponse}', [AgendaApplicationFormController::class, 'destroy'])->name('forms.destroy');

// Mailing lists
Route::delete('/mailList/{mailistid}/member/{memberid}', [MailListController::class, 'deleteMemberFromMailList'])->name('mailList.members.destroy');
Route::post('/mailList/{mailistid}/member', [MailListController::class, 'addMember'])->name('mailList.members.store');
Route::post('/mailList/massSync', [MailListController::class, 'massMemberMailListSync'])->name('mailList.massSync');

// Front end
Route::get('/zekeringen', [FrontEndController::class, 'zekeringen'])->name('front-end.zekeringen');
Route::get('/library', [FrontEndController::class, 'library'])->name('front-end.library');
Route::get('/agenda', [FrontEndController::class, 'agenda'])->name('front-end.agenda');
Route::get('/agenda/{agendaItem}', [FrontEndController::class, 'agendaDetailView'])->name('agenda.detail');
Route::get('/signup', [FrontEndController::class, 'publicSubscribe'])->name('front-end.signup');
Route::get('/home', [FrontEndController::class, 'home'])->name('front-end.home');
Route::get('/news', [FrontEndController::class, 'news'])->name('front-end.news');
Route::get('/news/{newsItem}', [FrontEndController::class, 'newsDetailView'])->name('front-end.news.detail');
Route::get('/memberlist', [FrontEndController::class, 'memberList'])->name('front-end.memberlist');
Route::get('/ical', [ICalController::class, 'getAgendaItemsICalObject'])->name('front-end.ical');
Route::get('/{menuItem:url_name}', [FrontEndController::class, 'showPage'])->name('front-end.page');
