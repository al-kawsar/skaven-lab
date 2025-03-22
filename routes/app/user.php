<?php
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

// * User Resource
Route::controller(UserController::class)->name('admin.user.')->prefix('admin/users')->middleware(['role:superadmin,admin', 'auth'])->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/api/get-data', 'getData')->name('get-data');
    Route::get('/add', 'create')->name('create');
    Route::get('/{user:id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
    Route::get('/{user:id}/edit', 'edit')->name('edit');
    Route::put('{user:id}', 'update')->name('update');
    Route::delete('/delete_all', 'destroyAll')->name('destroy.all');
    Route::delete('/{id:id}', 'destroy')->name('destroy');
});

// * Student Resource
Route::controller(StudentController::class)->name('admin.student.')->prefix('admin/students')->middleware(['role:superadmin,admin', 'auth'])->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/api/get-data', 'getData')->name('get-data');
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
Route::controller(TeacherController::class)->name('admin.teacher.')->prefix('admin/teachers')->middleware(['role:superadmin,admin', 'auth'])->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/api/get-data', 'getData')->name('get-data');
    Route::get('/add', 'create')->name('create');
    Route::get('/{user:id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
    Route::get('/{user:id}/edit', 'edit')->name('edit');
    Route::put('{user:id}', 'update')->name('update');
    Route::delete('/delete_all', 'destroyAll')->name('destroy.all');
    Route::delete('/{id:id}', 'destroy')->name('destroy');
});
