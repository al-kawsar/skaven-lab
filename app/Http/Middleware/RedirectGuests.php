<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectGuests
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
        if (Auth::check()) {
            return $next($request);
        }

        // Mark in the session that this was a redirect (without using message)
        $request->session()->put('guest_redirect', true);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk melanjutkan.'
            ], 401);
        }

        return redirect()->route('auth.login');
    }
}
