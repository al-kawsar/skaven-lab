<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * Maximum login attempts before rate limiting
     */
    protected const MAX_ATTEMPTS = 5;

    /**
     * Lockout duration in seconds
     */
    protected const LOCKOUT_TIME = 300; // 5 minutes

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show login form
     *
     * @return View
     */
    public function index(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Handle login request
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function doLogin(LoginRequest $request): JsonResponse
    {
        // Validate request with stricter rules
        $credentials = $request->validated();  // Get the IP address and throttle key
        $ipAddress = $request->ip();
        $throttleKey = Str::lower($credentials['username']) . '|' . $ipAddress;

        // Check if too many attempts from this combination of IP and username
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            Log::warning('Login attempt rate limited', [
                'username' => $credentials['username'],
                'ip' => $ipAddress,
                'user_agent' => $request->userAgent(),
                'available_in' => $seconds,
            ]);

            return response()->json([
                'success' => false,
                'message' => __('auth.throttle', ['seconds' => $seconds]),
            ], 429);
        }

        // Parse device info for security logs
        $deviceInfo = $this->parseDeviceInfo($request);

        // Remember preference
        $remember = $request->filled('remember');

        // Attempt login
        $result = $this->authService->attemptLogin($credentials, $remember, $deviceInfo);

        // Increment the rate limiter attempt counter if login failed
        if (!$result['success']) {
            RateLimiter::increment($throttleKey, self::LOCKOUT_TIME);
            return $this->sendFailedResponse($request, $result['message'], 401);
        }

        // On success, regenerate the session and return a 200 response
        $request->session()->regenerate();

        return $this->sendSuccessResponse($request, $result['message'], $result);
    }

    /**
     * Parse device info from request for security logging
     * 
     * @param Request $request
     * @return array
     */
    private function parseDeviceInfo(Request $request): array
    {
        // Get device info provided by client
        $rawDeviceInfo = $request->input('device_info');
        $deviceInfo = [];

        if ($rawDeviceInfo) {
            try {
                $deviceInfo = json_decode($rawDeviceInfo, true) ?? [];
            } catch (\Exception $e) {
                Log::warning('Error parsing device info', ['error' => $e->getMessage()]);
            }
        }

        // Always include these fields even if not provided by client
        return array_merge([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ], $deviceInfo);
    }

    /**
     * Switch to admin account - for development purposes only
     * This method should be protected in production with proper safeguards.
     *
     * @param Request $request
     * @param string $username
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchToAdmin(Request $request, string $username)
    {
        // This should only be enabled in local environment
        if (!app()->environment('local')) {
            abort(404);
        }

        // Validate the request - add a secret token for additional security
        if ($request->token !== config('app.debug_token')) {
            abort(403);
        }

        $credentials = [
            'username' => $username,
            'password' => config('app.debug_password')
        ];

        $result = $this->authService->attemptLogin($credentials, false);

        if ($result['success']) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('auth.login')->with('error', $result['message']);
    }
}
