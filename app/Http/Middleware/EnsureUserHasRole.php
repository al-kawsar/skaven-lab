<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$role): Response
    {

        // Validasi peran pengguna
        $userRole = $request->user()->role->name;
        if (!in_array($userRole, $role)) {
            return redirect()->back()->with([
                'type' => 'toast',
                'status' => 'warning',
                'message' => 'Anda tidak memiliki hak akses yang cukup untuk masuk ke halaman yang anda tuju.'
            ]);
        }


        return $next($request);
    }
}
