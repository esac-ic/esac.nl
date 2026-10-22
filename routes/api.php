<?php

use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ZekeringController;
use Illuminate\Support\Facades\Route;

// The URLs are used by the site's JavaScript, so they keep their names.
Route::controller(AgendaController::class)->group(function () {
    Route::get('agenda', 'getAgenda')->name('agenda.index');
    Route::get('agendaCategories', 'getCategories')->name('agenda-categories.index');
});

Route::get('menuItems', [MenuItemController::class, 'index'])->name('menu-items.index');
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/autoComplete', [UserController::class, 'autoComplete'])->name('users.auto-complete');
Route::get('user/registrations', [RegistrationController::class, 'index'])->name('registrations.index');

Route::controller(ZekeringController::class)->group(function () {
    Route::get('zekeringen', 'getZekeringen')->name('zekeringen.index');
    Route::post('zekeringen', 'storeZekering')->name('zekeringen.store');
    Route::post('subzekering', 'storeSubZekering')->name('subzekeringen.store');
    Route::delete('zekeringen/{zekering}', 'destroy')->name('zekeringen.destroy');
});
