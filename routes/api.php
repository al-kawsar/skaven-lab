<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lab\LabController;
use App\Http\Controllers\Borrowing\LabBorrowingController;
use App\Http\Controllers\User\TeacherController;
use App\Http\Controllers\User\StudentController;
use App\Http\Controllers\User\UserController;

// DataTables AJAX routes - requires authentication
Route::middleware(['web', 'auth'])->group(function () {
    // Lab routes
    Route::get('labs', [LabController::class, 'getData'])->name('labs.ajax');

    // Admin-only routes
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('borrowings/lab', [LabBorrowingController::class, 'getData'])->name('borrowing.lab.ajax');
        Route::get('users', [UserController::class, 'getData'])->name('user.ajax');
        Route::get('students', [StudentController::class, 'getData'])->name('student.ajax');
        Route::get('teachers', [TeacherController::class, 'getData'])->name('teacher.ajax');
    });

    // User borrowing history
    Route::get('borrowings/labs', [LabBorrowingController::class, 'userBorrowingData'])->name('borrowing.ajax');

});
