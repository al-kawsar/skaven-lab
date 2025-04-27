<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Borrowing\LabBorrowingController;

/*
|--------------------------------------------------------------------------
| Borrowing Management Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Lab borrowings
    Route::name('borrowing.lab.')->controller(LabBorrowingController::class)->prefix('borrowings/lab')->group(function () {

        // User lab borrowing management
        Route::controller(LabBorrowingController::class)->group(function () {
            // Admin lab borrowing management
            Route::middleware(['role:superadmin,admin'])->prefix('management')->name('admin.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/pending', 'pendingApprovals')->name('pending');
                Route::post('/{borrowing:id}/approve', 'approve')->name('approve');
                Route::post('/{borrowing:id}/reject', 'reject')->name('reject');
                Route::post('/{borrowing:id}/complete', 'complete')->name('complete');
            });


            // View user's lab borrowings
            Route::get('/', 'userBorrowing')->name('index');
            Route::get('/browse', 'listLabBorrowing')->name('list');
            // Route::get('/filter', 'filterBorrowings')->name('filter');

            // Create new lab borrowing
            Route::get('{lab:id}', 'create')->name('create');
            Route::post('{lab:id}', 'store')->name('store');

            // View, cancel, and print borrowings
            Route::get('/{borrowing:id}/detail', 'show')->name('show');
            Route::put('/{borrowing:id}/cancel', 'cancel')->name('cancel');
            Route::get('/{borrowing:id}/print', 'print')->name('print');

            // Resubmit borrowing
            Route::get('/{lab:id}/resubmit', 'resubmit')->name('resubmit');
            Route::post('/{lab:id}/resubmit', 'resubmitStore')->name('resubmit.store');

            Route::get('/{borrowing:id}/history', 'getHistory')->name('history');
        });
    });


    // Lab borrowings
    Route::prefix('item')->name('item.')->group(function () {
        Route::get('/', [LabBorrowingController::class, 'listLabBorrowing'])->name('index');
        Route::get('{item:id}', [LabBorrowingController::class, 'show'])->name('show');
        Route::get('{item:id}/borrowing', [LabBorrowingController::class, 'borrow'])->name('borrow');
        Route::post('{item:id}/borrowing', [LabBorrowingController::class, 'borrowStore'])->name('borrowing.store');
    });

    Route::post('/check-lab-availability', [LabBorrowingController::class, 'checkAvailability'])
        ->name('borrowing.lab.check');
});