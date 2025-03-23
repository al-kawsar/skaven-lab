<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;


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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $result = $this->authService->logout();

        if ($result['success']) {
            return redirect($result['redirect'])->with([
                'type' => 'toast',
                'status' => 'success',
                'message' => $result['message'] ?? 'Anda telah berhasil keluar dari sistem.'
            ]);
        }

        return $this->sendFailedResponse(
            $request,
            $result['message'] ?? 'Terjadi kesalahan saat proses logout.',
            $result['statusCode'] ?? 400
        );
    }
}
