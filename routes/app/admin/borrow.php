<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorrowingController;

Route::name('admin.borrow.')->controller(BorrowingController::class)->prefix('admin/borrowing')->middleware(['auth', 'role:superadmin,admin'])->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/api/get-data', 'getData')->name('get-data');
});


