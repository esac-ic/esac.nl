<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ZekeringController;
use Illuminate\Support\Facades\Route;

Route::get('/menuItems', [ApiController::class, 'getMenuItems']);
Route::get('/users', [ApiController::class, 'getUsers']);
Route::get('/users/autoComplete', [UserController::class, 'autoComplete'])->name('user.autoComplete');

Route::get('/agenda', [AgendaController::class, 'getAgenda']);
Route::get('/agendaCategories', [AgendaController::class, 'getCategories']);

Route::get('/zekeringen', [ZekeringController::class, 'getZekeringen']);
Route::post('/zekeringen', [ZekeringController::class, 'storeZekering']);
Route::post('/subzekering', [ZekeringController::class, 'storeSubZekering']);
Route::delete('/zekeringen/{id}', [ZekeringController::class, 'destroy']);

Route::get('/user/registrations', [RegistrationController::class, 'registrationsByUser']);
