<?php

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//api routes
Route::get('/menuItems', 'ApiController@getMenuItems');
Route::get('/setLanguage', 'SettingsController@setLanguage');
Route::get('/users', 'ApiController@getUsers');

Route::get('/agenda', 'Api\AgendaController@getAgenda');
Route::get('/agendaCategories','Api\AgendaController@getCategories');

Route::get('/zekeringen', 'Api\ZekeringController@getZekeringen');
Route::post('/zekeringen', 'Api\ZekeringController@storeZekering');
Route::post('/subzekering','Api\ZekeringController@storeSubZekering');
Route::delete('/zekeringen/{id}', 'Api\ZekeringController@destroy');