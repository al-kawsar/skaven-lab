<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lab\LabController;

/*
|--------------------------------------------------------------------------
| Laboratory Management Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::name('labs.')->controller(LabController::class)->prefix('labs')->group(function () {
        // * Public lab list
        Route::get('/', 'index')->name('index');

        // * Admin-only lab management - CRUD operations
        // Create routes
        Route::get('/create', 'create')->name('create')->middleware('role:superadmin,admin');
        Route::post('/', 'store')->name('store')->middleware('role:superadmin,admin');

        // Bulk actions
        Route::delete('/delete_all', 'destroyAll')->name('destroy.all')->middleware('role:superadmin,admin');

        // Slider image actions
        Route::delete('/lab-slider/{id}', 'destroySliderImage')->name('slider.destroy')->middleware('role:superadmin,admin');

        // Resource routes with ID parameter - MUST be last in this group
        Route::get('/{lab:id}', 'show')->name('show');
        Route::get('/{lab:id}/edit', 'edit')->name('edit')->middleware('role:superadmin,admin');
        Route::put('/{lab:id}', 'update')->name('update')->middleware('role:superadmin,admin');
        Route::delete('/{lab:id}', 'destroy')->name('destroy')->middleware('role:superadmin,admin');
    });
});
