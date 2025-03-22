<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

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
     * Handle user logout securely
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $result = $this->authService->logout();

        // Flash a success message to session
        if ($result['success']) {
            return redirect($result['redirect'])->with('success', $result['message']);
        }

        // If logout somehow fails, just redirect to login anyway
        return redirect()->route('auth.login');
    }
}
