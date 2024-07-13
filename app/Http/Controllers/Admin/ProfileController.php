<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.admin.profile.index');
    }


    public function update(ProfileUpdateRequest $request)
    {

        $payload = $request->validated();

        auth()->user()->update($payload);

        return to_route('settings.general')->with([
            'type' => 'alert',
            'status' => 'success',
            'message' => 'Berhasil Mengubah Profile'
        ]);

    }

}
