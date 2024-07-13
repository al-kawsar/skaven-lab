<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SiswaController;

Route::name('admin.')->middleware('auth')->prefix('admin')->group(function () {
    Route::controller(SiswaController::class)->name('siswa.')->prefix('siswa')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/api/get-data', 'getData')->name('get-data');
        Route::get('/add', 'create')->name('create');
        Route::get('/{siswa:id}', 'show')->name('show');
        Route::post('/', 'store')->name('store');
        Route::get('/{siswa:id}/edit', 'edit')->name('edit');
        Route::put('{siswa:id}', 'update')->name('update');
        Route::delete('/delete_all', 'destroyAll')->name('destroy.all');
        Route::delete('/{id:id}', 'destroy')->name('destroy');
        });
});