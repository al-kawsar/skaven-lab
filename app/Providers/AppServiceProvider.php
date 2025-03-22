<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(500),
                Limit::perMinute(3)->by($request->input('email')),
            ];
        });
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(1000);
        });

        // Load the custom CSS file
        Blade::component('image-uploader', \App\View\Components\ImageUploader::class);
        Blade::component('image-preview', \App\View\Components\ImagePreview::class);
    }
}
