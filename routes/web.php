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

//Admin routes
Route::group(['as' => 'beheer.', 'prefix' => 'beheer/', 'middleware' => 'auth'], function () {
    Route::get('home', [ManageController::class, 'index']);
    Route::resource('applicationForms', ApplicationFormController::class);

    //setting routes
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::put('/settings', [SettingsController::class, 'update']);
    Route::get('/settings/edit', [SettingsController::class, 'edit']);
});

//extra user routes
Route::get('users/old_members', [UserController::class, 'indexOldMembers'])->name('users.index-old-members');
Route::get('users/pending_members', [PendingUserController::class, 'indexPendingMembers'])->name('users.indexPendingMembers');
Route::get('users/exportUsers', [UserController::class, 'exportUsers'])->name('users.exportUsers');
Route::get('users/pennoMemberExportView', [UserController::class, 'pennoMemberExportSelect'])->name('users.pennoMemberExportView');
Route::get('users/pennoMemberExport', [UserController::class, 'pennoMemberExport'])->name('users.pennoMemberExport');
Route::get('users/exportOldUsers', [UserController::class, 'exportOldUsers']);
Route::patch('users/{user}/removeAsActiveMember', [UserController::class, 'removeAsActiveMember'])->name('users.removeAsActiveMember');
Route::patch('users/{user}/makeActiveMember', [UserController::class, 'makeActiveMember'])->name('users.makeActiveMember');
Route::patch('users/{user}/removeAsPendingMember', [PendingUserController::class, 'removeAsPendingMember'])->name('users.removeAsPendingMember');
Route::patch('users/{user}/approveAsPendingMember', [PendingUserController::class, 'approveAsPendingMember'])->name('users.approveAsPendingMember');

//user certification routes
Route::get('users/{user}/addCertificate', [UserCertificateController::class, 'addCertificate']);
Route::get('users/{user}/addCertificate/{certificate}', [UserCertificateController::class, 'editUserCertificate']);
Route::post('users/{user}/addCertificate', [UserCertificateController::class, 'saveCertificate']);
Route::patch('users/{user}/addCertificate/{certificate}', [UserCertificateController::class, 'updateUserCertificate']);
Route::delete('users/{user}/addCertificate/{certificate}', [UserCertificateController::class, 'deleteUserCertificate']);

//library routes
Route::get('books/exportLibrary', [LibraryController::class, 'exportLibrary']);

//crud routes
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
Route::post('images/upload', [StorageController::class, 'uploadImage']);
Route::delete('images/delete', [StorageController::class, 'deleteImage']);
Route::get('agendaItems/{agendaItem}/copy', [AgendaItemController::class, 'copy'])->name('copyAgendaItem');

Route::get('userEventLog', [UserEventLogEntryController::class, 'index'])->name('user-event-log-entry.index');
Route::post('userEventLog/export', [UserEventLogEntryController::class, 'export'])->name('user-event-log-entry.export');

//inschrijf routes
Route::get('forms/{agendaItem}', [UserApplicationFormController::class, 'showRegistrationForm']);
Route::get('forms/{agendaItem}/unregister/{fromAgendaItem?}', [UserApplicationFormController::class, 'unregister']);
Route::post('forms/{agendaItem}', [UserApplicationFormController::class, 'store']);
Route::get('forms/admin/{agendaItem}', [AgendaApplicationFormController::class, 'registerUser']);
Route::post('forms/admin/{agendaItem}', [AgendaApplicationFormController::class, 'saveRegistration']);
Route::get('forms/users/{agendaItem}', [AgendaApplicationFormController::class, 'index']);
Route::get("forms/users/{user}/detail/{agendaItem}", [AgendaApplicationFormController::class, 'show']);
Route::get('forms/users/{inschrijfId}/export', [AgendaApplicationFormController::class, 'exportData']);
Route::delete('forms/{agenda_id}/remove/{applicationResponseId}', [AgendaApplicationFormController::class, 'destroy']);

//maillist routes
Route::delete('/mailList/{mailistid}/member/{memberid}', [MailListController::class, 'deleteMemberFromMailList']);
Route::post('/mailList/{mailistid}/member', [MailListController::class, 'addMember']);
Route::post('/mailList/massSync', [MailListController::class, 'massMemberMailListSync']);
//front-end routes
Route::get('/zekeringen', [FrontEndController::class, 'zekeringen'])->name('front-end.zekeringen');
Route::get('/library', [FrontEndController::class, 'library'])->name('front-end.library');
Route::get('/agenda', [FrontEndController::class, 'agenda'])->name('front-end.agenda');
Route::get('/agenda/{agendaItem}', [FrontEndController::class, 'agendaDetailView'])->name('agenda.detail');
Route::get('/signup', [FrontEndController::class, 'publicSubscribe'])->name('front-end.signup');
Route::get('/home', [FrontEndController::class, 'home'])->name('front-end.home');
Route::get('/news', [FrontEndController::class, 'news'])->name('front-end.news');
Route::get('/news/{newsItem}', [FrontEndController::class, 'newsDetailView'])->name('front-end.news.detail');
Route::get('/memberlist', [FrontEndController::class, 'memberList'])->name('front-end.memberlist');
Route::get("/ical", [ICalController::class, 'getAgendaItemsICalObject'])->name('front-end.ical');
Route::get('/{menuItem}', [FrontEndController::class, 'showPage']);
