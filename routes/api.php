<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ZekeringController;
use Illuminate\Support\Facades\Route;

Route::get('/menuItems', [ApiController::class, 'getMenuItems'])->name('api.menuItems.index');
Route::get('/users', [ApiController::class, 'getUsers'])->name('api.users.index');
Route::get('/users/autoComplete', [UserController::class, 'autoComplete'])->name('user.autoComplete');

Route::get('/agenda', [AgendaController::class, 'getAgenda'])->name('api.agenda.index');
Route::get('/agendaCategories', [AgendaController::class, 'getCategories'])->name('api.agendaCategories.index');

Route::get('/zekeringen', [ZekeringController::class, 'getZekeringen'])->name('api.zekeringen.index');
Route::post('/zekeringen', [ZekeringController::class, 'storeZekering'])->name('api.zekeringen.store');
Route::post('/subzekering', [ZekeringController::class, 'storeSubZekering'])->name('api.subzekering.store');
Route::delete('/zekeringen/{id}', [ZekeringController::class, 'destroy'])->name('api.zekeringen.destroy');

Route::get('/user/registrations', [RegistrationController::class, 'registrationsByUser'])->name('api.user.registrations');
