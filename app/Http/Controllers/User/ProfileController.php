<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateRequest;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.profile.index');
    }


    public function update(UpdateRequest $request)
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
