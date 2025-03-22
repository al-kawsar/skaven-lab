<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LabController;

Route::middleware('auth')->group(function () {
    
    Route::name('admin.lab.')->controller(LabController::class)->prefix('admin/lab')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/api/get-data', 'getData')->name('get-data');
        Route::get('/api/get-user', 'getUser')->name('get-users');
        Route::get('/add', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{lab:id}/edit', 'edit')->name('edit');
        Route::put('/{lab:id}', 'update')->name('update');
        Route::delete('/delete_all', 'destroyAll')->name('destroy.all');
        Route::delete('/{lab:id}', 'destroy')->name('destroy');
        Route::delete('/lab-slider/{id}', 'destroySliderImage')->name('slider.destroy');
    });
});
