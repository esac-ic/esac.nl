<?php

use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ZekeringController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::controller(AgendaController::class)->group(function () {
        Route::get('agenda', 'getAgenda')->name('agenda.index');
        Route::get('agendaCategories', 'getCategories')->name('agendaCategories.index');
    });

    Route::get('menuItems', [ApiController::class, 'getMenuItems'])->name('menuItems.index');
    Route::get('users', [ApiController::class, 'getUsers'])->name('users.index');
    Route::get('users/autoComplete', [UserController::class, 'autoComplete'])->name('users.autoComplete');
    Route::get('user/registrations', [RegistrationController::class, 'registrationsByUser'])->name('user.registrations');

    Route::controller(ZekeringController::class)->group(function () {
        Route::get('zekeringen', 'getZekeringen')->name('zekeringen.index');
        Route::post('zekeringen', 'storeZekering')->name('zekeringen.store');
        Route::post('subzekering', 'storeSubZekering')->name('subzekering.store');
        Route::delete('zekeringen/{id}', 'destroy')->name('zekeringen.destroy');
    });
});
