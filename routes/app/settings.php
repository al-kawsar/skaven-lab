<?php
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::name('settings.')->controller(SettingController::class)->prefix('settings')->middleware(['auth'])->group(function () {
    Route::get('/account', 'accountView')->name('general');
    Route::get('/security', 'securityView')->name('security');
    Route::get('/security/logs', 'securityLogsView')->name('security.logs');

    Route::post('/account/update', 'updateGeneral')->name('general.update');
    Route::post('/security/password', 'updatePasswordSecurity')->name('security.password');
    Route::post('/security/login', 'updateLoginSecurity')->name('security.login');
    Route::post('/security/identification', 'updateIdentificationSecurity')->name('security.identification');
    Route::post('/security/notifications', 'updateNotificationSettings')->name('security.notifications');
    Route::post('/security/logs/settings', 'updateLogSettings')->name('security.logs.settings');
    Route::get('/activity/logs', 'activityLogsView')->name('activity.logs');

    Route::redirect('/', 'account');
});


