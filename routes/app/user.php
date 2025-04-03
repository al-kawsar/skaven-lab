<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\TeacherController;
use App\Http\Controllers\User\StudentController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProfileController;

/*
|--------------------------------------------------------------------------
| User Management Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // User profile
    Route::controller(ProfileController::class)->name('profile.')->prefix('profile')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/', 'update')->name('update');
    });

    Route::controller(ProfileController::class)->name('admin.report.')->prefix('report')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/s', 'index')->name('generate');
    });



    // * User Resource
    Route::controller(UserController::class)->name('user.')->prefix('users')->middleware(['role:superadmin,admin', 'auth'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'create')->name('create');
        Route::get('/{user:id}', 'show')->name('show');
        Route::post('/', 'store')->name('store');
        Route::get('/{user:id}/edit', 'edit')->name('edit');
        Route::put('{user:id}', 'update')->name('update');
        Route::delete('/delete_all', 'destroyAll')->name('destroy.all');
        Route::delete('/{id:id}', 'destroy')->name('destroy');
    });

    // * Student Resource
    Route::controller(StudentController::class)->name('student.')->prefix('students')->middleware(['role:superadmin,admin', 'auth'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'create')->name('create');

        // ? The statistics routes should appear BEFORE the show route with the parameter
        Route::get('/statistics/data', 'getStatistics')->name('statistics');
        Route::get('/statistics/view', 'statisticsView')->name('statistics.view');

        // ? Export & Import routes
        Route::get('/export/excel', 'exportToExcel')->name('export.excel');
        Route::post('/import', 'importFromExcel')->name('import');
        Route::get('/import/template', 'downloadImportTemplate')->name('import.template');

        // ? These routes with model binding should be AFTER the specific routes
        Route::get('/{siswa}', 'show')->name('show');
        Route::get('/{siswa}/edit', 'edit')->name('edit');
        Route::put('/{siswa}', 'update')->name('update');
        Route::post('/', 'store')->name('store');
        Route::delete('/delete_all', 'destroyAll')->name('destroy.all');
        Route::delete('/{siswa}', 'destroy')->name('destroy');
    });


    // * Teacher Resource
    Route::controller(TeacherController::class)->name('teacher.')->prefix('teachers')->middleware(['role:superadmin,admin', 'auth'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'create')->name('create');
        Route::get('/{user:id}', 'show')->name('show');
        Route::post('/', 'store')->name('store');
        Route::get('/{user:id}/edit', 'edit')->name('edit');
        Route::put('{user:id}', 'update')->name('update');
        Route::delete('/delete_all', 'destroyAll')->name('destroy.all');
        Route::delete('/{id:id}', 'destroy')->name('destroy');
    });

});