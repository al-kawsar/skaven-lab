<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\User\LabUserController;

Route::middleware('auth')->group(function () {
    // Admin Route
    Route::name('admin.lab.')->controller(LabController::class)->prefix('admin/lab')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/api/get-data', 'getData')->name('get-data');
        Route::get('/add', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{lab:id}/edit', 'edit')->name('edit');
        Route::put('{lab:id}', 'update')->name('update');
        Route::delete('/delete_all', 'destroyAll')->name('destroy.all');
        Route::delete('/{id:id}', 'destroy')->name('destroy');
    });

    // User Route
    Route::name('lab.')->controller(LabUserController::class)->prefix('lab')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{lab:id}', 'show')->name('show');
        Route::get('borrow/{lab:id}', 'borrow')->name('borrow');
        Route::post('borrow/{lab:id}', 'borrowStore')->name('borrow.store');
    });

    Route::name('borrow.')->controller(LabUserController::class)->prefix('borrowing')->middleware('auth')->group(function () {
        Route::get('/', 'borrowView')->name('view');
        Route::get('/api/get-data', 'borrowData')->name('get-data');
        Route::post('/cancel/{borrow:id}', 'borrowCancel')->name('cancel');
        Route::post('/approved/{borrow:id}', 'borrowApproved')->name('approved');
    });

});
