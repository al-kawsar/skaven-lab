<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::name('auth.')->prefix('auth')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {

        // Login
        Route::controller(LoginController::class)->group(function () {
            Route::get('/login', 'index')->name('login');
            Route::post('/login', 'doLogin')
                ->middleware(['throttle:login'])
                ->name('login.submit');
        });

        // Registration
        Route::controller(RegisterController::class)->group(function () {
            Route::get('/register', 'index')->name('register');
            Route::post('/register', 'doRegister')
                // ->middleware(['throttle:3,1'])
                ->name('register.submit');
        });

        // Password reset
        Route::controller(ForgotPasswordController::class)->group(function () {
            Route::get('/forgot-password', 'showForm')->name('password.request');
            Route::post('/forgot-password', 'sendResetLink')->name('password.email');
        });

        Route::controller(ResetPasswordController::class)->group(function () {
            Route::get('/reset-password/{token}', 'showForm')->name('password.reset');
            Route::post('/reset-password', 'reset')->name('password.update');
        });

    });

    // Authenticated routes
    Route::middleware(['auth'])->group(function () {
        // Logout
        Route::controller(LogoutController::class)->group(function () {
            Route::match(['get', 'post'], '/logout', 'logout')
                // ->middleware('throttle:6,1')
                ->name('logout');
        });

        // Change password
        Route::controller(ChangePasswordController::class)->group(function () {
            Route::get('/change-password', 'showForm')->name('password.change');
            Route::post('/change-password', 'doChange')
                // ->middleware(['throttle:5,1'])
                ->name('password.change.submit');
        });
    });

});