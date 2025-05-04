<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Borrowing\LabBorrowingController;
use App\Http\Controllers\User\TeacherController;
use App\Http\Controllers\User\StudentController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use App\Models\User;

// DataTables AJAX routes - requires authentication
Route::middleware(['web', 'auth'])->group(function () {


    // Admin-only routes
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::get('borrowings/lab', [LabBorrowingController::class, 'getData'])->name('borrowing.lab.ajax');
        Route::get('users/data', [UserController::class, 'getData'])->name('user.ajax');
        Route::get('students', [StudentController::class, 'getData'])->name('student.ajax');
        Route::get('teachers', [TeacherController::class, 'getData'])->name('teacher.ajax');
    });

    // User borrowing history
    Route::get('borrowings/labs', [LabBorrowingController::class, 'userBorrowingData'])->name('borrowing.ajax');

});

// Route untuk mendapatkan daftar user untuk dropdown filter
Route::middleware('auth:sanctum')->get('/users', function () {
    return response()->json([
        'data' => User::select('id', 'name', 'email')->get()
    ]);
})->name('api.users');