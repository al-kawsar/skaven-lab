<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LabController;

Route::middleware('auth')->group(function () {

    Route::name('admin.barang.')->controller(LabController::class)->prefix('barang')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
    });


    Route::name('admin.kategori.')->controller(LabController::class)->prefix('kategori')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::name('admin.lokasi.')->controller(LabController::class)->prefix('lokasi')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::name('admin.peminjaman.')->controller(LabController::class)->prefix('peminjaman')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('barang');
        Route::get('/pengembalian', 'pengembalian')->name('pengembalian');
        Route::get('/create', 'create')->name('create');
    });

    Route::name('admin.jadwal.')->controller(LabController::class)->prefix('jadwal')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('lab');
    });

    Route::name('admin.lokasi.detail.')->controller(LabController::class)->prefix('lokasi/detail')->middleware('role:superadmin,admin')->group(function () {
        Route::get('/', 'index')->name('index');
    });
});