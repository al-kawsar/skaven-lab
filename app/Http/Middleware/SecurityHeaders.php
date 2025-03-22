<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Content Security Policy
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://code.jquery.com; " .
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
            "img-src 'self' data:; " .
            "font-src 'self' data: https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.gstatic.com; " .
            "connect-src 'self'; " .
            "frame-src 'self'; " .
            "object-src 'none';"
        );

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Enable XSS filtering in browser
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Strict transport security (only in production)
        if (!app()->environment('local')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Feature Policy
        $response->headers->set(
            'Feature-Policy',
            "geolocation 'self'; " .
            "microphone 'none'; " .
            "camera 'none'; " .
            "payment 'none';"
        );

        // Permissions Policy (newer version of Feature Policy)
        $response->headers->set(
            'Permissions-Policy',
            "geolocation=(self), " .
            "microphone=(), " .
            "camera=(), " .
            "payment=()"
        );

        // Cache control for sensitive pages
        if ($this->isSensitivePage($request)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        }

        return $response;
    }

    /**
     * Determine if the request is for a sensitive page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isSensitivePage(Request $request): bool
    {
        // Add routes or patterns for sensitive pages
        $sensitivePaths = [
            'login',
            'register',
            'password/*',
            'settings/*',
            'profile/*',
            'admin/*',
        ];

        foreach ($sensitivePaths as $path) {
            if (Str::is($path, $request->path())) {
                return true;
            }
        }

        return false;
    }
}