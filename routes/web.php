<?php

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;


require __DIR__ . '/app/auth.php';

Route::redirect('/', '/dashboard');

Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->middleware('auth')->name('dashboard');

Route::name('admin.')->middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->name('profile.')->prefix('profile')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/', 'update')->name('update');
    });
    Route::controller(ReportController::class)->name('report.')->prefix('report')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
    });
});

require __DIR__ . '/app/admin/lab.php';
require __DIR__ . '/app/admin/borrow.php';
require __DIR__ . '/app/admin/siswa.php';
require __DIR__ . '/app/admin/guru.php';


require __DIR__ . '/app/settings.php';
