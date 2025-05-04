<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Borrowing\LabBorrowingController;
use App\Http\Controllers\Borrowing\LabBorrowingHistoryController;

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

                // History routes
                Route::get('/history', [LabBorrowingHistoryController::class, 'index'])->name('history');
                Route::get('/history/data', [LabBorrowingHistoryController::class, 'getData'])->name('history.data');
            });

            // View user's lab borrowings
            Route::get('/', 'userBorrowing')->name('index');

            // Ajax routes for getting data
            Route::get('/data', 'userBorrowingData')->name('data');
            Route::get('/management/data', 'getData')->name('management.data');
            Route::get('/pending/data', 'pendingApprovalsData')->name('pending.data');

            // Create new lab borrowing - no longer linked to lab model
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');

            // View, cancel, and print borrowings
            Route::get('/{borrowing:id}/detail', 'show')->name('show');
            Route::put('/{borrowing:id}/cancel', 'cancel')->name('cancel');
            Route::get('/{borrowing:id}/print', 'print')->name('print');

            // Resubmit borrowing - no longer dependent on lab model
            Route::get('/resubmit', 'resubmit')->name('resubmit');
            Route::post('/resubmit/store', 'resubmitStore')->name('resubmit.store');

            // History
            Route::get('/{borrowing:id}/history', 'showHistory')->name('history');

            // Filter
            Route::get('/filter', 'filter')->name('filter');

            // Recurring borrowing routes
            Route::get('/{borrowing}/instances', 'getRecurringInstances')->name('recurring.instances');
            Route::post('/{borrowing}/cancel-all', 'cancelAllRecurring')->name('recurring.cancel');
        });
    });

    // Lab borrowings
    Route::prefix('item')->name('item.')->group(function () {
        Route::get('/', [LabBorrowingController::class, 'listLabBorrowing'])->name('index');
        Route::get('{item:id}', [LabBorrowingController::class, 'show'])->name('show');
        Route::get('{item:id}/borrowing', [LabBorrowingController::class, 'borrow'])->name('borrow');
        Route::post('{item:id}/borrowing', [LabBorrowingController::class, 'borrowStore'])->name('borrowing.store');
    });

    // Check availability route
    Route::post('/check-lab-availability', [LabBorrowingController::class, 'checkAvailability'])
        ->name('borrowing.lab.check');

});