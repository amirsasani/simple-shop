<?php

namespace App\Http\Controllers;

use App\Setting;

class HomeController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    public function dashboard()
    {
        $setting = Setting::all()->firstWhere('setting_key', 'bankacount_number');
        return view('admin.home', compact('setting'));
    }
}
