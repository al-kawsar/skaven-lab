<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('pages.auth.login');
    }

    public function doLogin(LoginRequest $request)
    {

        $credentials = $request->only('username', 'password');
        $remember = request()->has('remember');

        if (auth()->attempt(['email' => $credentials['username'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => 'Kredensial yang Anda masukkan tidak sesuai',
        ]);
    }

}
