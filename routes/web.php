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

Route::name('auth.')->prefix('auth')->middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'doLogin')->name('doLogin');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::post('register', 'doRegister')->name('doRegister');
    });

    Route::controller(LogoutController::class)->withoutMiddleware('guest')->group(function () {
        Route::get('logout', 'logout')->name('logout');
    })->middleware('auth');

    Route::controller(ChangePasswordController::class)->withoutMiddleware('guest')->group(function () {
        Route::post('change-password', 'doChange')->name('change-password');
    })->middleware('auth');
});

require __DIR__ . '/app/profile.php';
require __DIR__ . '/app/admin/user.php';
require __DIR__ . '/app/admin/lab.php';
require __DIR__ . '/app/admin/borrow.php';
require __DIR__ . '/app/settings.php';

