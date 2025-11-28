<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    //return the home page
    return redirect('/home', 301);
});

Auth::routes(['register' => false]);

//Admin routes
Route::group(['as' => 'beheer.', 'prefix' => 'beheer/', 'middleware' => 'auth'], function () {
    Route::get('home', 'ManageController@index');
    Route::resource('applicationForms', 'ApplicationForm\ApplicationFormController');

    //setting routes
    Route::get('/settings', 'SettingsController@index');
    Route::put('/settings', 'SettingsController@update');
    Route::get('/settings/edit', 'SettingsController@edit');
});

//extra user routes
Route::get('users/old_members', 'UserController@indexOldMembers');
Route::get('users/pending_members', 'PendingUserController@indexPendingMembers')->name('users.indexPendingMembers');
Route::get('users/exportUsers', 'UserController@exportUsers');
Route::get('users/exportOldUsers', 'UserController@exportOldUsers');
Route::patch('users/{user}/removeAsActiveMember', 'UserController@removeAsActiveMember')->name('users.removeAsActiveMember');;
Route::patch('users/{user}/makeActiveMember', 'UserController@makeActiveMember')->name('users.makeActiveMember');
Route::patch('users/{user}/removeAsPendingMember', 'PendingUserController@removeAsPendingMember')->name('users.removeAsPendingMember');
Route::patch('users/{user}/approveAsPendingMember', 'PendingUserController@approveAsPendingMember')->name('users.approveAsPendingMember');

//user certification routes
Route::get('users/{user}/addCertificate', 'UserCertificateController@addCertificate');
Route::get('users/{user}/addCertificate/{certificate}', 'UserCertificateController@editUserCertificate');
Route::post('users/{user}/addCertificate', 'UserCertificateController@saveCertificate');
Route::patch('users/{user}/addCertificate/{certificate}', 'UserCertificateController@updateUserCertificate');
Route::delete('users/{user}/addCertificate/{certificate}', 'UserCertificateController@deleteUserCertificate');

//library routes
Route::get('books/exportLibrary', 'LibraryController@exportLibrary');

//mailist routes
Route::delete('/mailList/{mailistid}/member/{memberid}', 'MailListController@deleteMeberOfMailList');
Route::post('/mailList/{mailistid}/member', 'MailListController@addMember');
Route::post('/mailList/massSync', 'MailListController@massMemberMailListSync');

//crud routes
Route::resource('users', 'UserController');
Route::resource('rols', 'RolController');
Route::resource('pages', 'PaginaBeheerController');
Route::resource('certificates', 'CertificateController');
Route::resource('agendaItems', 'AgendaItemController');
Route::resource('agendaItemCategories', 'AgendaItemCategoryController');
Route::resource('newsItems', 'NewsItemController');
Route::resource('mailList', 'MailListController');
Route::post('/signup', 'PendingUserController@storePendingUser')->name('user.signup');
Route::resource('books', 'LibraryController');
Route::post('images/upload', 'StorageController@uploadImage');
Route::delete('images/delete', 'StorageController@deleteImage');
Route::get('agendaItems/{agendaItem}/copy', 'AgendaItemController@copy')->name('copyAgendaItem');

Route::get('userEventLog', 'UserEventLogEntryController@index');
Route::post('userEventLog/export', 'UserEventLogEntryController@export');
Route::delete('userEventLog/{entry}', 'UserEventLogEntryController@destroy');

//inschrijf routes
Route::get('forms/{agendaItem}', array('uses' => 'ApplicationForm\UserApplicationFormController@showRegistrationForm'));
Route::get('forms/{agendaItem}/unregister/{fromAgendaItem?}', 'ApplicationForm\UserApplicationFormController@unregister');
Route::post('forms/{agendaItem}', array('uses' => 'ApplicationForm\UserApplicationFormController@store'));
Route::get('forms/admin/{agendaItem}', 'ApplicationForm\AgendaApplicationFormController@registerUser');
Route::post('forms/admin/{agendaItem}', 'ApplicationForm\AgendaApplicationFormController@saveRegistration');
Route::get('forms/users/{agendaItem}', array('uses' => 'ApplicationForm\AgendaApplicationFormController@index'));
Route::get("forms/users/{user}/detail/{agendaItem}", "ApplicationForm\AgendaApplicationFormController@show");
Route::get('forms/users/{inschrijfId}/export', array('uses' => 'ApplicationForm\AgendaApplicationFormController@exportData'));
Route::delete('forms/{agenda_id}/remove/{applicationResponseId}', 'ApplicationForm\AgendaApplicationFormController@destroy');

//front-end routes
Route::get('/zekeringen', 'FrontEndController@zekeringen')->name('frontEnd.zekeringen');
Route::get('/library', 'FrontEndController@library')->name('front.library');
Route::get('/agenda', 'FrontEndController@agenda')->name('frontEnd.agenda');
Route::get('/agenda/{agendaItem}', 'FrontEndController@agendaDetailView')->name('agenda.detail');
Route::get('/signup', 'FrontEndController@publicSubscribe')->name('front-end.signup');
Route::get('/home', 'FrontEndController@home')->name('front-end.home');
Route::get('/news', 'FrontEndController@news')->name("front-end.news");
Route::get('/news/{newsItem}', 'FrontEndController@newsDetailView')->name('front-end.news.detail');
Route::get('/memberlist', 'FrontEndController@memberList')->name('front-end.memberlist');
Route::get("/ical", "ICalController@getAgendaItemsICalObject")->name("front-end.ical");
Route::get('/{menuItem}', 'FrontEndController@showPage');

//setting routes
Route::get('/management/settings', 'SettingsController@index');
Route::put('/management/settings', 'SettingsController@update');
Route::get('/management/settings/edit', 'SettingsController@edit');
