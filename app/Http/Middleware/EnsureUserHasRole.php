<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * The redirect route when access is denied
     * 
     * @var string
     */
    protected const REDIRECT_ROUTE = 'dashboard';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles Allowed roles for this route
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return $this->handleUnauthorized($request, 'Unauthenticated access attempt');
        }

        $user = $request->user();
        
        // Check if user has role relation
        if (!$user->role) {
            Log::warning('User without role attempted to access protected route', [
                'user_id' => $user->id,
                'route' => $request->path(),
                'ip' => $request->ip()
            ]);
            
            return $this->handleUnauthorized($request);
        }

        // Validate user role
        $userRole = $user->role->name;
        if (!in_array($userRole, $roles, true)) {
            Log::info('Unauthorized role access attempt', [
                'user_id' => $user->id,
                'user_role' => $userRole,
                'required_roles' => $roles,
                'route' => $request->path(),
                'ip' => $request->ip()
            ]);
            
            return $this->handleUnauthorized($request);
        }

        return $next($request);
    }

    /**
     * Handle unauthorized access attempt
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $logMessage
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleUnauthorized(Request $request, ?string $logMessage = null): Response
    {
        if ($logMessage) {
            Log::warning($logMessage, [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'route' => $request->path()
            ]);
        }
        
        return redirect()->route(self::REDIRECT_ROUTE)->with([
            'type' => 'toast',
            'status' => 'warning',
            'message' => 'Anda tidak memiliki hak akses yang cukup untuk masuk ke halaman yang anda tuju.'
        ]);
    }
}
