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
Route::get('users/pending_members', 'PendingUserController@indexPendingMembers');
Route::get('users/exportUsers', 'UserController@exportUsers');
Route::get('users/exportOldUsers', 'UserController@exportOldUsers');
Route::patch('users/{user}/removeAsActiveMember', 'UserController@removeAsActiveMember');
Route::patch('users/{user}/makeActiveMember', 'UserController@makeActiveMember');
Route::patch('users/{user}/removeAsPendingMember', 'PendingUserController@removeAsPendingMember');
Route::patch('users/{user}/approveAsPendingMember', 'PendingUserController@approveAsPendingMember');

//user certification routes
Route::get('users/{user}/addCertificate', 'UserCertificateController@addCertificate');
Route::get('users/{user}/addCertificate/{certificate}', 'UserCertificateController@editUserCertificate');
Route::post('users/{user}/addCertificate', 'UserCertificateController@saveCertificate');
Route::patch('users/{user}/addCertificate/{certificate}', 'UserCertificateController@updateUserCertificate');
Route::delete('users/{user}/addCertificate/{certificate}', 'UserCertificateController@deleteUserCertificate');

//library routes
Route::get('books/exportLibrary', 'LibraryController@exportLibrary');

//crud routes
Route::resource('users', 'UserController');
Route::resource('rols', 'RolController');
Route::resource('pages', 'PaginaBeheerController');
Route::resource('certificates', 'CertificateController');
Route::resource('agendaItems', 'AgendaItemController');
Route::resource('agendaItemCategories', 'AgendaItemCategoryController');
Route::resource('newsItems', 'NewsItemController');
Route::resource('mailList', 'MailListController');
Route::post('/signup', 'PendingUserController@storePendingUser');
Route::resource('books', 'LibraryController');
Route::post('images/upload', 'StorageController@uploadImage');
Route::delete('images/delete', 'StorageController@deleteImage');
Route::get('agendaItems/{agendaItem}/copy', 'AgendaItemController@copy')->name('copyAgendaItem');

//inschrijf routes
Route::get('forms/{agendaItem}', array('as' => 'editSchedule', 'uses' => 'ApplicationForm\UserApplicationFormController@showRegistrationForm'));
Route::get('forms/{agendaItem}/unregister/{fromAgendaItem?}', 'ApplicationForm\UserApplicationFormController@unregister');
Route::post('forms/{agendaItem}', array('as' => 'editSchedule', 'uses' => 'ApplicationForm\UserApplicationFormController@store'));
Route::get('forms/admin/{agendaItem}', 'ApplicationForm\AgendaApplicationFormController@registerUser');
Route::post('forms/admin/{agendaItem}', 'ApplicationForm\AgendaApplicationFormController@saveRegistration');
Route::get('forms/users/{agendaItem}', array('as' => 'editSchedule', 'uses' => 'ApplicationForm\AgendaApplicationFormController@index'));
Route::get("forms/users/{user}/detail/{agendaItem}", "ApplicationForm\AgendaApplicationFormController@show");
Route::get('forms/users/{inschrijfId}/export', array('as' => 'editSchedule', 'uses' => 'ApplicationForm\AgendaApplicationFormController@exportData'));
Route::delete('forms/{agenda_id}/remove/{applicationResponseId}', 'ApplicationForm\AgendaApplicationFormController@destroy');

Route::resource('forms', 'InschrijfController');

//mailist routes
Route::delete('/mailList/{mailistid}/member/{memberid}', 'MailListController@deleteMeberOfMailList');
Route::post('/mailList/{mailistid}/member', 'MailListController@addMember');

//front-end routes
Route::get('/zekeringen', 'FrontEndController@zekeringen');
Route::get('/library', 'FrontEndController@library');
Route::get('/library', 'FrontEndController@library');
Route::get('/agenda', 'FrontEndController@agenda');
Route::get('/agenda/{agendaItem}', 'FrontEndController@agendaDetailView')->name('agenda.detail');
Route::get('/signup', 'FrontEndController@publicSubscribe');
Route::get('/home', 'FrontEndController@home');
Route::get('/news', 'FrontEndController@news');
Route::get('/news/{newsItem}', 'FrontEndController@newsDetailView');
Route::get('/memberlist', 'FrontEndController@memberList');
Route::get("/ical", "ICalController@getAgendaItemsICalObject");
Route::get('/{menuItem}', 'FrontEndController@showPage');

//setting routes
Route::get('/management/settings', 'SettingsController@index');
Route::put('/management/settings', 'SettingsController@update');
Route::get('/management/settings/edit', 'SettingsController@edit');
