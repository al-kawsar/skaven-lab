<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Main entry point for all web routes.
|
*/

// Home/Landing page
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard (protected)
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/sa', DashboardController::class)
    ->middleware(['auth'])
    ->name('notifications.index');

// Authentication routes
require __DIR__ . '/app/auth.php';

// User module routes
require __DIR__ . '/app/user.php';

// Laboratory module routes
require __DIR__ . '/app/lab.php';

// Equipment/Inventory module routes
require __DIR__ . '/app/inventory.php';

// Borrowing module routes
require __DIR__ . '/app/borrowing.php';

// Reports module routes
require __DIR__ . '/app/report.php';

// Settings module routes
require __DIR__ . '/app/setting.php';
