<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
)
->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo(function (Request $request) {
        session()->flash('type', 'toast');
        session()->flash('status', 'warning');
        session()->flash('message', 'Silakan login terlebih dahulu untuk melanjutkan.');
        return route('auth.login');
    });

    $middleware->web([
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
            SecurityHeaders::class, // Add security headers to all responses
        ]);
    $middleware->alias([
        'role' => EnsureUserHasRole::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
    ]);
})
->withExceptions(function (Exceptions $exceptions) {
        // Development environment handling
    if (!app()->isProduction()) {
        $exceptions->render(function (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ], 500);
        });
    }

        // Authentication Exceptions
    $exceptions->render(function (AuthenticationException $e, Request $request) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'data' => null
            ], 401);
        }
        return redirect()->guest(route('auth.login'));
    });

        // Validation Exceptions
    $exceptions->render(function (ValidationException $e, Request $request) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        return redirect()->back()->withErrors($e->errors())->withInput();
    });

        // Not Found Exceptions
    $exceptions->render(function (NotFoundHttpException $e, Request $request) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
                'data' => null
            ], 404);
        }
        return response()->view('errors.404', [], 404);
    });

        // Generic Exceptions
    $exceptions->render(function (\Exception $e, Request $request) {
        $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => app()->isProduction() ? 'Server Error' : $e->getMessage(),
                'data' => null
            ], $status);
        }

        return response()->view('errors.500', [
            'message' => app()->isProduction() ? 'Server Error' : $e->getMessage()
        ], $status);
    });
})->create();
