<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Default role ID for new users
     */
    protected const DEFAULT_ROLE_ID = 5;

    /**
     * Show registration page
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.auth.register');
    }

    /**
     * Handle user registration
     * 
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doRegister(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();

            $validatedData['role_id'] = self::DEFAULT_ROLE_ID;

            $validatedData['password'] = Hash::make($validatedData['password']);

            $validatedData['remember_token'] = Str::random(60);

            $user = User::create($validatedData);

            DB::commit();

            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'ip' => $request->ip()
            ]);


            auth()->login($user);


            $request->session()->regenerate();

            return redirect()->route('dashboard')->with([
                'type' => 'toast',
                'status' => 'success',
                'message' => 'Pendaftaran berhasil, Anda telah masuk.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);

            return $this->sendFailedResponse(
                $request,
                'Terjadi kesalahan saat mendaftar. Silakan coba lagi.',
                500
            );
        }
    }
}
