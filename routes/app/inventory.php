<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lab\LabController;
use App\Http\Controllers\Inventory\EquipmentController;
use App\Http\Controllers\Inventory\EquipmentCategoryController;
use App\Http\Controllers\Inventory\EquipmentLocationController;

/*
|--------------------------------------------------------------------------
| Inventory Management Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Admin routes (protected by admin role)
    Route::middleware(['role:superadmin,admin'])->group(function () {

        // Equipment management routes
        Route::prefix('equipment')->name('admin.barang.')->controller(EquipmentController::class)->group(function () {
            // Basic CRUD routes
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/data', 'getData')->name('getData');

            // Export/Import routes
            Route::get('/export/{type}', 'export')->name('export');
            Route::get('/template/download', 'downloadTemplate')->name('downloadTemplate');
            Route::post('/import', 'import')->name('import');

            // Bulk actions
            Route::post('/bulk-destroy', 'bulkDestroy')->name('bulkDestroy');
            Route::delete('/', 'destroyAll')->name('destroyAll');

            // Related data routes
            Route::get('/categories', 'getCategories')->name('categories');
            Route::get('/locations', 'getLocations')->name('locations');

            // Single item routes
            Route::get('/{equipment}/ajax', 'show')->name('show');
            Route::get('/{equipment}', 'detail')->name('detail');
            Route::put('/{equipment:id}', 'update')->name('update');
            Route::delete('/{equipment}', 'destroy')->name('destroy');

        });

        // Equipment category routes
        Route::prefix('equipment-category')->name('admin.kategori.')->controller(EquipmentCategoryController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'getData')->name('getData');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/export/{type}', 'export')->name('export');
            Route::get('/template/download', 'downloadTemplate')->name('downloadTemplate');
            Route::post('/import', 'import')->name('import');
            Route::post('/bulk-destroy', 'bulkDestroy')->name('bulkDestroy');
        });

        // Equipment location routes
        Route::prefix('equipment-location')->name('admin.lokasi.')->controller(EquipmentLocationController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'getData')->name('getData');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/export/{type}', 'export')->name('export');
            Route::get('/template/download', 'downloadTemplate')->name('downloadTemplate');
            Route::post('/import', 'import')->name('import');
            Route::post('/bulk-destroy', 'bulkDestroy')->name('bulkDestroy');
        });

        // Equipment borrowing routes
        Route::prefix('equipment-borrowing')->name('admin.peminjaman.')->group(function () {
            Route::get('/', [EquipmentController::class, 'borrowingIndex'])->name('barang');
            Route::get('/return', [EquipmentController::class, 'returnIndex'])->name('pengembalian');
        });

        // Lab schedule routes
        Route::prefix('lab-schedule')->name('admin.jadwal.')->group(function () {
            Route::get('/', [LabController::class, 'index'])->name('lab');
        });

    });

    // User routes (for all authenticated users)
    Route::prefix('item')->name('item.')->group(function () {
        Route::get('/', [EquipmentController::class, 'userIndex'])->name('index');
        Route::get('/{id}', [EquipmentController::class, 'userShow'])->name('show');
    });

});
