<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MemberType;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsRequest;
use App\Settings\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SettingsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-settings'),
        ];
    }

    public function edit(SiteSettings $settings): View
    {
        return view('admin.settings.edit', [
            'settings' => $settings,
            'memberTypes' => MemberType::cases(),
        ]);
    }

    public function update(SettingsRequest $request, SiteSettings $settings): RedirectResponse
    {
        $settings->fill($request->validated())->save();

        return to_route('admin.settings.edit')->with('message', 'Settings updated');
    }
}
