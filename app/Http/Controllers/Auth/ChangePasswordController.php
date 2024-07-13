<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function doChange(ChangePasswordRequest $request)
    {
        $payload = $request->validated();

        $new_password = bcrypt($payload['password']);
        $user = auth()->user();

        $check_password = password_verify($payload['old-password'], $user->password);

        if (!$check_password) {
            return to_route('settings.security')->withErrors('Password yang Anda masukkan salah.');
        }

        $user->update([
            'password' => $new_password
        ]);

        return to_route('settings.security')->with([
            'type' => 'alert',
            'status' => 'success',
            'message' => 'Password berhasil diubah'
        ]);
    }
}
