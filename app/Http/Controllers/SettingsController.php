<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
    }

    public function index()
    {
        $settings = Setting::all();
        return view('beheer.settings.index', compact('settings'));
    }

    public function edit()
    {
        $settings = Setting::all();
        return view('beheer.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = Setting::all();
        foreach ($request->get('setting', []) as $key => $value) {
            $setting = $settings->where('name', '=', $key)->first();
            $setting->value = $value;
            $setting->save();
        }

        Session::flash("message", 'Settings updated');
        return redirect('/beheer/settings');
    }
}
