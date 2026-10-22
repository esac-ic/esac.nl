<?php

use App\Http\Controllers\Api\EventCategoryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\ZekeringController;
use Illuminate\Support\Facades\Route;

// The URLs are used by the site's JavaScript, so they keep their names.
Route::get('agenda', [EventController::class, 'index'])->name('events.index');
Route::get('agendaCategories', [EventCategoryController::class, 'index'])->name('event-categories.index');

Route::get('menuItems', [MenuItemController::class, 'index'])->name('menu-items.index');
Route::get('users', [MemberController::class, 'index'])->name('members.index');
Route::get('users/autoComplete', [MemberController::class, 'autocomplete'])->name('members.autocomplete');
Route::get('user/registrations', [RegistrationController::class, 'index'])->name('registrations.index');

Route::controller(ZekeringController::class)->group(function () {
    Route::get('zekeringen', 'index')->name('zekeringen.index');
    Route::post('zekeringen', 'store')->name('zekeringen.store');
    Route::post('subzekering', 'storeChild')->name('zekeringen.children.store');
    Route::delete('zekeringen/{zekering}', 'destroy')->name('zekeringen.destroy');
});
