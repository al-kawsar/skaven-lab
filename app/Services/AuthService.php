<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthService
{
    /**
     * Attempt to authenticate a user
     *
     * @param array $credentials
     * @param bool $remember
     * @param array|null $deviceInfo
     * @return array
     */
    public function attemptLogin(array $credentials, bool $remember = false, ?array $deviceInfo = null): array
    {
        // Get the throttle key
        $throttleKey = Str::lower($credentials['username']) . '|' . request()->ip();

        // Check if too many attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return [
                'success' => false,
                'message' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ];
        }

        // Increment the rate limiter attempt counter
        RateLimiter::increment($throttleKey);

        // Additional security checks before attempting auth
        if (!$this->validateLoginRequest($credentials)) {
            return [
                'success' => false,
                'message' => "Validasi login gagal: Terdeteksi input mencurigakan"
            ];
        }

        // Attempt to authenticate
        if (
            Auth::attempt([
                'email' => $credentials['username'],
                'password' => $credentials['password']
            ], $remember)
        ) {
            // Clear rate limiting on successful login
            RateLimiter::clear($throttleKey);

            // Log the successful login with device info
            $this->logSuccessfulLogin($deviceInfo);

            // Get redirect URL based on user role
            $redirectUrl = $this->getRedirectUrlForUser(Auth::user());

            return [
                'success' => true,
                'message' => "Login berhasil. Selamat datang kembali, " . Auth::user()->name,
                'redirect' => $redirectUrl
            ];
        }

        // Log the failed login attempt
        $this->logFailedLogin($credentials['username'], $deviceInfo);

        return [
            'success' => false,
            'message' => "Login gagal: Username atau password tidak valid"
        ];
    }

    /**
     * Additional validation for login requests
     *
     * @param array $credentials
     * @return bool
     */
    private function validateLoginRequest(array $credentials): bool
    {
        // Check for suspicious input (potential SQL injection or XSS)
        if ($this->containsSuspiciousInput($credentials['username'])) {
            Log::warning('Suspicious login attempt detected', [
                'username' => $credentials['username'],
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            return false;
        }

        // Check if user exists
        $user = User::where('email', $credentials['username'])->first();
        if ($user) {
            // Additional validation can be added here if needed
            return true;
        }

        return true;
    }

    /**
     * Check if input contains suspicious patterns
     *
     * @param string $input
     * @return bool
     */
    private function containsSuspiciousInput(string $input): bool
    {
        $patterns = [
            '/(\%27)|(\')|(\-\-)|(\%23)|(#)/i', // Basic SQL injection
            '/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/i', // Basic XSS
            '/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/i', // IMG tags
            '/(javascript|vbscript|expression|applet|script|embed|object|iframe|frame|frameset|alert|confirm|prompt|eval)\W/i' // JS and other code
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log successful login
     *
     * @param array|null $deviceInfo
     * @return void
     */
    private function logSuccessfulLogin(?array $deviceInfo = null): void
    {
        $user = Auth::user();

        // Create log data with device info
        $logData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'login_at' => now()->toIso8601String()
        ];

        // Add device info if available
        if ($deviceInfo) {
            $logData['device_info'] = $deviceInfo;
        }

        Log::info('User logged in successfully', $logData);

        // Update last login timestamp and device info
        $user->last_login_at = now();
        $user->last_login_ip = request()->ip();

        // Store device info in user record if available
        if ($deviceInfo && property_exists($user, 'last_login_device')) {
            $user->last_login_device = json_encode($deviceInfo);
        }

        $user->save();
    }

    /**
     * Log failed login attempt
     *
     * @param string $username
     * @param array|null $deviceInfo
     * @return void
     */
    private function logFailedLogin(string $username, ?array $deviceInfo = null): void
    {
        // Create log data
        $logData = [
            'username' => $username,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'attempt_at' => now()->toIso8601String()
        ];

        // Add device info if available
        if ($deviceInfo) {
            $logData['device_info'] = $deviceInfo;
        }

        Log::warning('Failed login attempt', $logData);
    }

    /**
     * Get redirect URL based on user role
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return string
     */
    private function getRedirectUrlForUser($user): string
    {
        // Check user role and return appropriate redirect
        if ($user->hasRole('superadmin')) {
            return route('dashboard');
        } elseif ($user->hasRole('admin')) {
            return route('dashboard');
        } elseif ($user->hasRole('teacher')) {
            return route('dashboard');
        } elseif ($user->hasRole('student')) {
            return route('dashboard');
        }

        // Default redirect
        return route('dashboard');
    }

    /**
     * Log the user out
     *
     * @return array
     */
    public function logout(): array
    {
        // Get current user before logout
        $user = Auth::user();

        // Perform logout
        Auth::logout();

        // Invalidate the session
        request()->session()->invalidate();

        // Regenerate CSRF token
        request()->session()->regenerateToken();

        // Log the logout
        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip(),
                'time' => now()->toIso8601String()
            ]);
        }

        return [
            'success' => true,
            'message' => "Logout berhasil. Anda telah keluar dari sistem.",
            'redirect' => route('auth.login')
        ];
    }

    /**
     * Change user password
     *
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return array
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): array
    {
        // Verify current password
        if (!Hash::check($currentPassword, $user->password)) {
            return [
                'success' => false,
                'message' => "Perubahan password gagal: Password saat ini tidak benar"
            ];
        }

        // Check if new password is the same as the current one
        if (Hash::check($newPassword, $user->password)) {
            return [
                'success' => false,
                'message' => "Perubahan password gagal: Password baru harus berbeda dari password saat ini"
            ];
        }

        // Update password
        $user->password = Hash::make($newPassword);
        $user->password_changed_at = now();
        $user->save();

        // Log password change
        Log::info('User changed password', [
            'user_id' => $user->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return [
            'success' => true,
            'message' => "Password berhasil diubah. Password baru Anda sudah aktif."
        ];
    }
}