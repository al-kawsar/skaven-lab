<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\StudentController;

Route::redirect('/', '/dashboard');
Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

Route::name('auth.')->prefix('auth')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::controller(LoginController::class)->group(function () {
            Route::get('login', 'index')->name('login');
            Route::post('login', 'doLogin')
                ->middleware(['throttle:5,1'])
                ->name('doLogin');
        });

        Route::controller(RegisterController::class)->group(function () {
            Route::post('register', 'doRegister')
                ->middleware(['throttle:3,1'])
                ->name('doRegister');
        });
    });

    // Authenticated routes
    Route::middleware(['auth'])->group(function () {
        Route::controller(LogoutController::class)->group(function () {
            Route::match(['get', 'post'], 'logout', 'logout')
                ->middleware('throttle:6,1')
                ->name('logout');
        });

        Route::controller(ChangePasswordController::class)->group(function () {
            Route::post('change-password', 'doChange')
                ->middleware(['throttle:5,1'])
                ->name('change-password');
        });
    });
});

require __DIR__ . '/app/profile.php';
require __DIR__ . '/app/user.php';
require __DIR__ . '/app/lab.php';
require __DIR__ . '/app/item.php';
require __DIR__ . '/app/borrow.php';
require __DIR__ . '/app/settings.php';

