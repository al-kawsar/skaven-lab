<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;


Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->name('profile.')->prefix('profile')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/', 'update')->name('update');
    });
    Route::controller(ReportController::class)->name('admin.report.')->prefix('report')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/s', 'index')->name('generate');
    });
});
