<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function accountView()
    {
        return view('pages.settings.general');
    }
    public function securityView()
    {
        return view('pages.settings.security');
    }
}
