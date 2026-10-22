<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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

    public function show(): View
    {
        return view('beheer.settings.show', ['settings' => Setting::all()]);
    }

    public function edit(): View
    {
        return view('beheer.settings.edit', ['settings' => Setting::all()]);
    }

    public function update(SettingsRequest $request): RedirectResponse
    {
        foreach ($request->validated('setting') as $name => $value) {
            // An emptied field is saved as an empty value.
            Setting::query()->where('name', $name)->update(['value' => $value ?? '']);
        }

        return to_route('beheer.settings.show')->with('message', 'Settings updated');
    }
}
