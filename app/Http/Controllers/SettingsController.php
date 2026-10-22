<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('authorize:' . Role::Administrator->value),
        ];
    }

    public function index(): View
    {
        $settings = Setting::all();
        return view('beheer.settings.index', compact('settings'));
    }

    public function edit(): View
    {
        $settings = Setting::all();
        return view('beheer.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $settings = Setting::all();
        foreach ($request->input('setting', []) as $key => $value) {
            $setting = $settings->where('name', '=', $key)->first();
            $setting->value = $value;
            $setting->save();
        }

        Session::flash("message", 'Settings updated');
        return to_route('beheer.settings.index');
    }
}
