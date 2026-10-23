<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MemberType;
use App\Http\Controllers\Controller;
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

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => Setting::query()->pluck('value', 'name'),
            'memberTypes' => MemberType::cases(),
        ]);
    }

    public function update(SettingsRequest $request): RedirectResponse
    {
        foreach ($request->validated('settings') as $name => $value) {
            // An emptied field is saved as an empty value.
            Setting::query()->where('name', $name)->update(['value' => $value ?? '']);
        }

        return to_route('admin.settings.edit')->with('message', 'Settings updated');
    }
}
