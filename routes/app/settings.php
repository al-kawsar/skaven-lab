<?php
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::name('settings.')->controller(SettingController::class)->prefix('settings')->middleware(['auth'])->group(function () {
    Route::get('/account', 'accountView')->name('general');
    Route::get('/security', 'securityView')->name('security');
    Route::redirect('/', 'account');
});


