<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GuruController;

Route::controller(GuruController::class)->name('admin.guru.')->prefix('admin/users')->middleware(['role:superadmin,admin', 'auth'])->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/api/get-data', 'getData')->name('get-data');
    Route::get('/add', 'create')->name('create');
    Route::get('/{guru:id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
    Route::get('/{guru:id}/edit', 'edit')->name('edit');
    Route::put('{guru:id}', 'update')->name('update');
    Route::delete('/delete_all', 'destroyAll')->name('destroy.all');
    Route::delete('/{id:id}', 'destroy')->name('destroy');
});
