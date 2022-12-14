<?php

use App\Http\Controllers\BookingCrudController;
use App\Http\Controllers\CustomerCrudController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('customers', CustomerCrudController::class);
    Route::patch('bookings/{id}/paid', [BookingCrudController::class, 'paid'])->name('bookings.paid');
    Route::get('bookings/get-total-amount', [BookingCrudController::class, 'getTotalAmount'])->name('bookings.get-total-amount');
    Route::resource('bookings', BookingCrudController::class);
});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
