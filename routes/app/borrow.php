<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\LabUserController;

// * Lab Admin
Route::prefix('borrowing')->name('admin.borrow.')->middleware(['auth', 'role:superadmin,admin'])->group(function () {
    Route::get('/', [BorrowingController::class, 'index'])->name('index');
    Route::get('/api/get-data', [BorrowingController::class, 'getData'])->name('get-data');
});

// * Lab User
Route::prefix('lab')->name('lab.')->group(function () {
    Route::get('/', [LabUserController::class, 'index'])->name('index');
    Route::get('{lab:id}', [LabUserController::class, 'show'])->name('show');
    Route::get('{lab:id}/borrow', [LabUserController::class, 'borrow'])->name('borrow');
    Route::post('{lab:id}/borrow', [LabUserController::class, 'borrowStore'])->name('borrow.store');
    Route::get('/detail/{borrow}', [LabUserController::class, 'borrowDetail'])->name('detail');
});

// * Pinjam Barang
Route::prefix('item')->name('item.')->group(function () {
    Route::get('/', [LabUserController::class, 'index'])->name('index');
    Route::get('{item:id}', [LabUserController::class, 'show'])->name('show');
    Route::get('{item:id}/borrow', [LabUserController::class, 'borrow'])->name('borrow');
    Route::post('{item:id}/borrow', [LabUserController::class, 'borrowStore'])->name('borrow.store');
});

// * Peminjaman Saya
Route::prefix('my-borrowing')->name('borrow.')->middleware('auth')->group(function () {
    Route::get('/', [LabUserController::class, 'borrowView'])->name('view');
    Route::get('/api/get-data', [LabUserController::class, 'borrowData'])->name('get-data');
    Route::get('/detail/{borrow}', [LabUserController::class, 'borrowDetail'])->name('detail');
    Route::post('/cancel/{borrow}', [LabUserController::class, 'borrowCancel'])->name('cancel');
    Route::get('/print/{borrow}', [LabUserController::class, 'borrowPrint'])->name('print');
    Route::get('/filter', [LabUserController::class, 'borrowFilter'])->name('filter');
});
