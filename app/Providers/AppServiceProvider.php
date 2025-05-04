<?php

namespace App\Providers;

use App;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        App::setLocale('id');
        Carbon::setLocale('id');
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(60),
                Limit::perMinute(10)->by($request->input('username')),
                Limit::perMinute(10)->by($request->ip()),
            ];
        });
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(1000);
        });

        Blade::component('image-uploader', \App\View\Components\ImageUploader::class);
        Blade::component('image-preview', \App\View\Components\ImagePreview::class);

        // Force HTTPS dalam produksi atau jika diakses melalui Ngrok
        if (
            env('APP_ENV') !== 'local' ||
            strpos(request()->server('HTTP_HOST') ?? '', 'ngrok') !== false
        ) {
            URL::forceScheme('https');
        }
    }

}
