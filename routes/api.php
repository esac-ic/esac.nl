<?php

use App\Http\Controllers\Api\EventCategoryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ZekeringController;
use Illuminate\Support\Facades\Route;

// The URLs are used by the site's JavaScript, so they keep their names.
Route::get('agenda', [EventController::class, 'index'])->name('events.index');
Route::get('agendaCategories', [EventCategoryController::class, 'index'])->name('event-categories.index');

Route::middleware('auth')->controller(ZekeringController::class)->group(function () {
    Route::get('zekeringen', 'index')->name('zekeringen.index');
    Route::post('zekeringen', 'store')->name('zekeringen.store');
    Route::post('subzekering', 'storeChild')->name('zekeringen.children.store');
    Route::delete('zekeringen/{zekering}', 'destroy')->name('zekeringen.destroy')->can('manage-zekeringen');
});
