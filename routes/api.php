<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ZekeringController;
use Illuminate\Support\Facades\Route;

Route::get('events', [EventController::class, 'index'])->name('events.index');

Route::middleware('auth')->controller(ZekeringController::class)->group(function () {
    Route::get('zekeringen', 'index')->name('zekeringen.index');
    Route::post('zekeringen', 'store')->name('zekeringen.store');
    Route::delete('zekeringen/{zekering}', 'destroy')->name('zekeringen.destroy')->can('manage-zekeringen');
});
