<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SettingsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-settings'),
        ];
    }

    public function index(): View
    {
        return view('beheer.settings.index', ['settings' => Setting::all()]);
    }

    public function edit(): View
    {
        return view('beheer.settings.edit', ['settings' => Setting::all()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $settings = Setting::all();
        foreach ($request->input('setting', []) as $key => $value) {
            $setting = $settings->where('name', '=', $key)->first();
            $setting->value = $value;
            $setting->save();
        }

        return to_route('beheer.settings.index')->with('message', 'Settings updated');
    }
}
