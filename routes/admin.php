<?php

use App\Http\Controllers\BookingSettingContoller;
use App\Http\Controllers\ServiceCrudController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Admin\UserCrudController;

Route::name('admin.')->group(function() {

    Route::resource('users', UserCrudController::class);
    Route::resource('services', ServiceCrudController::class);
    Route::get('settings', [BookingSettingContoller::class, 'index'])->name('settings');
    Route::patch('setting-update', [BookingSettingContoller::class, 'update']);
});
