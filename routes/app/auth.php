<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

Route::name('auth.')->prefix('auth')->middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'doLogin')->name('doLogin');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('register', 'index')->name('register');
        Route::post('register', 'doRegister')->name('doRegister');
    });

    Route::controller(LogoutController::class)->withoutMiddleware('guest')->group(function () {
        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller(ChangePasswordController::class)->withoutMiddleware('guest')->group(function () {
        Route::post('change-password', 'doChange')->name('change-password');
    })->middleware('auth');
});