<?php

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
Route::group(['as' => 'beheer.', 'prefix' => 'beheer/', 'middleware' => 'auth'], function(){
    Route::get('home','ManageController@index');
    Route::resource('applicationForms', 'ApplicationForm\ApplicationFormController');

    //setting routes
    Route::get('/settings','SettingsController@index');
    Route::put('/settings','SettingsController@update');
    Route::get('/settings/edit','SettingsController@edit');
});


//extra user routes
Route::get('users/old_members','UserController@indexOldMembers');
Route::get('users/pending_members','PendingUserController@indexPendingMembers'); 
Route::get('users/exportUsers','UserController@exportUsers');
Route::get('users/exportRegistrationInfo', 'PendingUserController@getRegistrationExportData')->name('export-registration-info');
Route::patch('users/{user}/removeAsActiveMember', 'UserController@removeAsActiveMember');
Route::patch('users/{user}/makeActiveMember', 'UserController@makeActiveMember');
Route::patch('users/{user}/removeAsPendingMember', 'PendingUserController@removeAsPendingMember');
Route::patch('users/{user}/approveAsPendingMember', 'PendingUserController@approveAsPendingMember');

//user certification routes
Route::get('users/{user}/addCertificate','UserCertificateController@addCertificate');
Route::get('users/{user}/addCertificate/{certificate}','UserCertificateController@editUserCertificate');
Route::post('users/{user}/addCertificate','UserCertificateController@saveCertificate');
Route::patch('users/{user}/addCertificate/{certificate}','UserCertificateController@updateUserCertificate');
Route::delete('users/{user}/addCertificate/{certificate}','UserCertificateController@deleteUserCertificate');

//crud routes
Route::resource('users','UserController');
Route::resource('rols', 'RolController');
Route::resource('pages', 'PaginaBeheerController');
Route::resource('certificates', 'CertificateController');
Route::resource('agendaItems', 'AgendaItemController');
Route::resource('agendaItemCategories', 'AgendaItemCategorieController');
Route::resource('newsItems', 'NewsItemController');
Route::resource('mailList', 'MailListController');
Route::post('/lidworden', 'PendingUserController@storePendingUser');
Route::resource('books', 'LibraryController');
Route::resource('photoAlbums', 'PhotoAlbumController');
Route::post('images/upload', 'StorageController@uploadImage');
Route::delete('images/delete', 'StorageController@deleteImage');
Route::get('agendaItems/{agendaItem}/copy', 'AgendaItemController@copy')->name('copyAgendaItem');

//inschrijf routes
Route::get('forms/{agendaItem}', array('as' => 'editSchedule', 'uses' => 'ApplicationForm\UserApplicationFormController@showRegistrationForm'));
Route::get('forms/{agendaItem}/unregister', 'ApplicationForm\UserApplicationFormController@unregister');
Route::post('forms/{agendaItem}', array('as' => 'editSchedule', 'uses' => 'ApplicationForm\UserApplicationFormController@store'));
Route::get('forms/admin/{agendaItem}','ApplicationForm\AgendaApplicationFormController@registerUser');
Route::post('forms/admin/{agendaItem}','ApplicationForm\AgendaApplicationFormController@saveRegistration');
Route::get('forms/users/{agendaItem}', array('as' => 'editSchedule', 'uses' => 'ApplicationForm\AgendaApplicationFormController@index'));
Route::get("forms/users/{user}/detail/{agendaItem}","ApplicationForm\AgendaApplicationFormController@show");
Route::get('forms/users/{inschrijfId}/export', array('as' => 'editSchedule', 'uses' => 'ApplicationForm\AgendaApplicationFormController@exportData'));
Route::delete('forms/{agenda_id}/remove/{applicationResponseId}', 'ApplicationForm\AgendaApplicationFormController@destroy');

Route::resource('forms', 'InschrijfController');

//mailist routes
Route::delete('/mailList/{mailistid}/member/{memberid}','MailListController@deleteMeberOfMailList');
Route::post('/mailList/{mailistid}/member','MailListController@addMember');

//photo routes
Route::post('/photoalbums/{albumId}', 'PhotoController@addPhotoToAlbum');
Route::post('/photoalbums','PhotoController@addAlbum');

//front-end routes
Route::get('/photoalbums/{albumId}','PhotoController@index')->name('PhotoAlbum');
Route::get('/photo/{id}','PhotoController@getPhotos')->name('getPhotos');
Route::get('/photoalbums','frontEndController@photoAlbums');
Route::get('/photo/{id}','PhotoController@getPhotos')->name('getPhotos');
Route::get('/zekeringen','frontEndController@zekeringen');
Route::get('/bibliotheek','frontEndController@library');
Route::get('/agenda','frontEndController@agenda');
Route::get('/agenda/{agendaItem}','frontEndController@agendaDetailView')->name('agenda.detail');
Route::get('/lidworden','frontEndController@publicSubscribe');
Route::get('/signup','frontEndController@publicSubscribe');
Route::get('/home','frontEndController@home');
Route::get('/nieuws','frontEndController@news');
Route::get('/nieuws/{newsItem}','frontEndController@newsDetailView');
Route::get('/ledenlijst','frontEndController@memberList');
Route::get("/ical", "ICalController@getAgendaItemsICalObject");
Route::get('/{menuItem}','frontEndController@showPage');

//setting routes
Route::get('/beheer/settings','SettingsController@index');
Route::put('/beheer/settings','SettingsController@update');
Route::get('/beheer/settings/edit','SettingsController@edit');

// intro routes
Route::resource('beheer/intro/packages', 'IntroPackageController', [
    'names' => 'beheer.intro.packages',
]);
