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

        // Daftar domain yang diizinkan
        $trustedDomains = [
            'cdn.jsdelivr.net',
            'cdnjs.cloudflare.com',
            'code.jquery.com',
            'fonts.googleapis.com',
            'fonts.gstatic.com',
        ];

        // Daftar Vite development URLs
        $viteUrls = app()->environment('local') ? [
            'localhost:5173',
            '127.0.0.1:5173',
            '[::1]:5173'
        ] : [];

        // Gabungkan trusted domains dengan Vite URLs jika di local
        $scriptSrcDomains = array_merge(
            $trustedDomains,
            array_map(fn($url) => "http://{$url}", $viteUrls),
            array_map(fn($url) => "https://{$url}", $viteUrls)
        );

        // Base CSP directives
        $cspDirectives = [
            // Default fallback
            "default-src 'self'",

            // Script sources
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' " . implode(' ', $scriptSrcDomains),

            // Explicitly set script-src-elem to match script-src
            "script-src-elem 'self' 'unsafe-inline' 'unsafe-eval' " . implode(' ', $scriptSrcDomains),

            // Style sources
            "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdnjs.cloudflare.com fonts.googleapis.com",

            // Image sources
            "img-src 'self' data: blob:",

            // Font sources
            "font-src 'self' data: cdn.jsdelivr.net cdnjs.cloudflare.com fonts.gstatic.com",

            // Connect sources (for Vite HMR in development)
            "connect-src 'self'" . (app()->environment('local') ?
                " ws://localhost:5173 ws://127.0.0.1:5173 ws://[::1]:5173" .
                " http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173" : ""),

            // Frame sources
            "frame-src 'self'",

            // Object sources
            "object-src 'none'",

            // Media sources
            "media-src 'self'",

            // Manifest sources
            "manifest-src 'self'"
        ];

        // Set Content Security Policy
        $response->headers->set(
            'Content-Security-Policy',
            implode('; ', array_unique($cspDirectives))
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

        // Permissions Policy (replaces Feature Policy)
        $response->headers->set(
            'Permissions-Policy',
            "geolocation=(self), microphone=(), camera=(), payment=(), " .
            "autoplay=(), fullscreen=(self), picture-in-picture=(*)"
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